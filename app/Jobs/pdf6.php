<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use PdfMerger;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Quotation,Company,Project,SettingPDFfooter,Users,Customer,QuotationContactInformation,CustomerContact,SettingPDF1,SettingPDF2,BOMSetting,SideScreenItem,QuotationShipToInformation,QuotationVersion,Item,BOMDetails,AddIronmongery,LippingSpecies,Option,SettingIntumescentSeals2,IntumescentSealLeafType,ItemMaster,ConfigurableItems,GlassType,OverpanelGlassGlazing,SideScreenItemMaster,SettingPDFDocument,GlazingSystem};
use DB;

class pdf6 implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public  $quatationId;

    public  $versionID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($quatationId, $versionID)
    {
        $this->quatationId = $quatationId;
        $this->versionID = $versionID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $quatationId = $this->quatationId;
        $versionID = $this->versionID;

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

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;

        // Elevation Drawing
        $elevTbl = '';
        // $ed = Item::where('QuotationId',$quatationId)->get();
        $ed = Item::join('item_master','item_master.itemID','=','items.itemId')->join("quotation_version_items",function($join): void{
            $join->on("quotation_version_items.itemID","=","items.itemId")
                ->on("quotation_version_items.itemmasterID","=","item_master.id");
        })
        ->join('quotation','quotation.id','=','items.QuotationId')
        ->where('items.QuotationId', $quatationId)
        ->where('quotation_version_items.version_id', $versionID)->select('items.*','item_master.doorNumber','quotation.configurableitems')->groupBy('item_master.itemID')->get();

        $TotalItems = count($ed->toArray());

        $PageBreakCount = 1;
        $PageBreakCounts = 1;

        foreach ($ed as $tt) {
            $getLeaf = IntumescentSealLeafType::where('id',$tt->IntumescentLeafType)->select('id','leaf_type_key','door_thickness')->first();
            if($getLeaf){
                $fire = $tt->FireRating . ' - ' . $getLeaf->leaf_type_key . ' (' . $getLeaf->door_thickness . 'mm)';
            }
            else{
                $fire = $tt->FireRating;
            }

            if($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s'){
                $FireRatingActualValue = 'FD30';
                $tt->FireRating = 'FD30';
            }elseif($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s'){
                $FireRatingActualValue = 'FD60';
                $tt->FireRating = 'FD60';
            }else{
                $FireRatingActualValue  =  $tt->FireRating;
            }
            
           // sidelight
            if($tt->FireRating == 'FD30s'){
                $tt->FireRating = 'FD30';

            }elseif($tt->FireRating == 'FD60s'){
                $tt->FireRating = 'FD60';

            }

            $configurationDoor = configurationDoor($tt->configurableitems);
            $fireRatingDoor = fireRatingDoor($FireRatingActualValue);

            // dd($tt);
            // $tt->DoorsetType = 'SD';
            if($tt->DoorsetType == 'leaf_and_a_half'){
                $tt->DoorsetType = 'DD';
            }

            $sidelight = '';
            $frameImageLeftMargin = 10;
            if($tt->DoorsetType == "SD") {
                $frameImageLeftMargin = 0;
            }

            // $tt->SideLight1 = 'Yes';
            // $tt->SideLight2BeadingType = "Splayed_Flush";

            if(($tt->SideLight1 == 'Yes' || $tt->SideLight2 == 'Yes') && ($tt->SideLight2BeadingType != "Splayed_Bolection")){
                $sidelight = 'sidelight';
                $frameImageLeftMargin = 192;
                if($tt->DoorsetType == "SD") {
                    $frameImageLeftMargin = 222;
                }

                // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');

                $SideLightGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                $SideLightGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
            }

            // dd($frameImageLeftMargin);



            $countDoorNumber = ItemMaster::where('itemID', $tt->itemId)->count();
            $DoorNumber = ItemMaster::where('itemID', $tt->itemId)->get();

            $doorNo = '';

            foreach ($DoorNumber as $bb) {
                $doorNo .= '<span style="padding-left:5px;">' . $bb->doorNumber . '</span>';
            }
            
            $species = LippingSpecies::where('id', $tt->FrameMaterial)->first();
            if ($species != '') {
                $frameMaterial = $species->SpeciesName;
                $GlazingBeadSpecies = $species->SpeciesName;
            } else {
                $frameMaterial = empty($tt->FrameMaterial) ? 'N/A' : $tt->FrameMaterial;

                $GlazingBeadSpecies = 'N/A';
            }

            // Overpanel/Fanlight Section :- OP Glazing Bead Species
            $OPspecies = LippingSpecies::where('id', $tt->OPGlazingBeadSpecies)->first();
            $OPGlazingBeadSpecies = $OPspecies != '' ? $OPspecies->SpeciesName : 'N/A';

            $DoorFrameImage = "";
            $VisionPanelGlazingImage = "";



            // dd($tt->toArray());

            $GlazingSystems = $tt->GlazingSystems;
            // $GlazingSystems = "Norsound_Vision_60";
            $GlazingBeads = $tt->GlazingBeads;
            // $GlazingBeads = "Splayed_Bolection";

            // $tt->FireRating = "FD60";
            $GlazingSystems = GlazingSystemsImage($GlazingSystems,$GlazingBeads,$tt->FireRating);
            $VisionPanelGlazingImageStructure = $GlazingSystems['VisionPanelGlazingImageStructure'];
            $VisionPanelGlazingImageRight = $GlazingSystems['VisionPanelGlazingImageRight'];
            $VisionPanelGlazingImageLeft = $GlazingSystems['VisionPanelGlazingImageLeft'];
            $VisionPanelGlazingImageStructure_Frame = $GlazingSystems['VisionPanelGlazingImageStructure_Frame'];

            if ($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes") {

                $VisionPanelGlazingImage = '<div style=" width:300px;margin: 0 auto;position: relative;">
                        <img style="width: 300px;z-index: 1;" src="' . $VisionPanelGlazingImageStructure . '" alt="">
                        <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $VisionPanelGlazingImageRight . '" alt="">
                    </div>';
            }


            // echo $VisionPanelGlazingImage;die;
            // dd(\Config::get('constants.base64Images.SplayedBolectionFD60Right'));
            // dd($VisionPanelGlazingImage);
            // $tt->FrameType = "Rebated_Frame";
            $VisionPanelGlazingImageStructure = $VisionPanelGlazingImageStructure_Frame;

            $FrameImageStructureLeft = \Config::get('constants.base64Images.FrameImageStructureLeft');
            $FrameImageStructureRight = \Config::get('constants.base64Images.FrameImageStructureRight');
            $FrameTypeLeft = "";
            $FrameTypeCommon = "";
            if (!empty($tt->FrameType) && $tt->FrameType == "Plant_on_Stop") {
                $FrameTypeLeft = \Config::get('constants.base64Images.FramePlantOnStopLeft');
                $FrameTypeRight = \Config::get('constants.base64Images.FramePlantOnStopRight');
                $FrameTypeCommon = \Config::get('constants.base64Images.FramePlantOnStopCommon');
            } elseif (!empty($tt->FrameType) && ($tt->FrameType == "Rebated_Frame")) {
                $FrameTypeLeft = \Config::get('constants.base64Images.FrameRebatedLeft');
                $FrameTypeRight = \Config::get('constants.base64Images.FrameRebatedRight');
                $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
            } elseif (!empty($tt->FrameType) && $tt->FrameType == "Scalloped") {
                // if (!empty($tt->FrameType) && $tt->FrameType != "Scalloped") {
                $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                // }
            }

            $FixedSpaceBlock = \Config::get('constants.base64Images.FixedSpaceBlock');
            $RemainingSpaceBlock = \Config::get('constants.base64Images.RemainingSpaceBlock');
            $FullBlock = \Config::get('constants.base64Images.FullBlock');
            $RemainingSpaceBlockScallopedLeft=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedLeft');
            $RemainingSpaceBlockScallopedRight=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedRight');
            $FixedSpaceBlockScallopedLeft=\Config::get('constants.base64Images.FixedSpaceBlockScallopedLeft');
            $FixedSpaceBlockScallopedRight=\Config::get('constants.base64Images.FixedSpaceBlockScallopedRight');


            $remainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedLeft;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                } else {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                $FrameImageStructureLeft = $FixedSpaceBlock;
                $FrameImageStructureRight = $RemainingSpaceBlock;
            } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {
                $FrameImageStructureLeft = $RemainingSpaceBlock;
                $FrameImageStructureRight = $FixedSpaceBlock;
            } else {
                $FrameImageStructureLeft = $RemainingSpaceBlock;
                $FrameImageStructureRight = $RemainingSpaceBlock;
            }
            
            $leaf1RemainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock = $FixedSpaceBlockScallopedLeft;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $FixedSpaceBlock;
                    // $FullBlock =$FixedSpaceBlockScallopedLeft;
                    $FullBlock =$FixedSpaceBlockScallopedRight;
                } else {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock =$FixedSpaceBlockScallopedRight;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoor > $leaf1RemainingWidth) {
                $FrameImageStructureLeftLeaf1 = $FixedSpaceBlock;
                $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
            } elseif ($tt->DistanceFromTheEdgeOfDoor < $leaf1RemainingWidth) {
                $FrameImageStructureLeftLeaf1 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf1 = $FixedSpaceBlock;
            } else {
                $FrameImageStructureLeftLeaf1 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
            }

            $leaf2RemainingWidth = $tt->LeafWidth2 - ($tt->Leaf2VPWidth + $tt->DistanceFromTheEdgeOfDoorforLeaf2);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoorforLeaf2 > $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $FixedSpaceBlock;
                } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 < $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $FixedSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlockScallopedRight;
                } else {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 > $leaf2RemainingWidth) {
                $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf2 = $FixedSpaceBlock;
            } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 < $leaf2RemainingWidth) {
                $FrameImageStructureLeftLeaf2 = $FixedSpaceBlock;
                $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
            } else {
                $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
            }

// dd($tt->Leaf2VisionPanel != 'Yes');
            $redstripRightCommonClass = $tt->IntumescentLeapingSealLocation.'_right_strip_'.$tt->DoorsetType;
            $redstripLeftCommonClass = $tt->IntumescentLeapingSealLocation.'_left_strip_'.$tt->DoorsetType;
            switch ($tt->DoorsetType) {
                case "SD":
                    // $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding:10px 30px;position: relative;margin-left: '.$frameImageLeftMargin.'px;">
                                <div style="position: relative;top: 12px;">';

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            // dd("972");class="'.$redstripLeftCommonClass.'"
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
                                    margin-top: 25px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // dd("985");
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
                                    margin-top: 15px;"></div>

                                    <div style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
                                    margin-top: 42px;"></div>';
                        }
                    }


                    if($sidelight !== "" && $tt->SideLight1 == 'Yes'){

                        $DoorFrameImage .= '<div style="
                        width: 0px;
                        margin: 0 auto;
                        position: relative;
                        margin-left: -66px;
                        top: -131px;
                        left: -119px;
                        ">
                        <img style="
                        z-index: 999;
                        position: absolute;
                        width: 69px;
                        height: 71px;
                        margin-left: -26px;
                        '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' :  '133') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;'
                                :
                                'top:'. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;')
                        .'
                        " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >


                     </div>
                     <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-47' : '5').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-251' : '-265').'px;">
                        <img style="width:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : '194').'px;" alt="" src="'.$FrameTypeCommon.'">
                     </div>
                     <div style="
                        width: 0px;
                        margin: 0 auto;
                        position: relative;
                        margin-left: -79px;
                        top: 2px;
                        left: -4px;
                        transform: rotate(180deg);
                        ">
                        <img style="
                           z-index: 999;
                           position: absolute;
                           margin-top: 86px;
                           width: 69px;
                           height: 71px;
                           margin-left: -8px;
                           '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') :((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;')
                            .'
                           " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >

                     </div>
                     <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-83' : '-87').'px;">
                        <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : '196').'px;" alt="" src="'.$FrameTypeCommon.'">
                     </div>';

                    }


                    $DoorFrameImage .= '<div  style="position: absolute; top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7') .'px;left: -44px;">
                                        <img style="width:76px;" src="' . $FrameTypeLeft . '" alt="">
                                    </div>
                                </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">
                                    <img style="
                                        width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '155' : '165') .'px;
                                        position: relative;
                                        top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '5' : '-17') .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '26' : '15') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ' height: 65px;' : '') .
        '" src="' . $FrameImageStructureLeft . '" alt="">
                                    </div>
                                    <div style="width:430px;">
                                        <div style="
                                            width:200px;
                                            margin: 0 auto;
                                            position: relative;
                                            margin-left: -66px;
                                            top: -168px;
                                            left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '248' : '245') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '237' : '235')) .'px;
                                            ">

                                            <img style="
                                            z-index: 999;
                                            ' .(($tt->FireRating == 'FD30') ?
                            'width: 125px;
                             margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '118' : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '68')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : '82') .'px;' :
                            'width: 108px; margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '0' : '4') .'px;
                                                margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '118' : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '69')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '80'))) .'px;' . '" src="' . $VisionPanelGlazingImageStructure . '" alt="">



                                        </div>
                                        <div style="
                                            width:200px;
                                            margin-top:120px;
                                            position: relative;
                                            top: -493px;
                                            left: 265px;
                                            ">

                                            <img style="
                                            z-index: 999;
                                            ' . (($tt->FireRating == 'FD30') ?

                                             'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '117' : '125') .'px;
                                                 margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '135' : '132') :  ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124' : '121')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') : '34') .'px;
                                                height: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '73' : '84') .'px;'
                            :
                            'width: 124px; margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '135' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124.5' : '122.5')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '25.5' : '31.5') .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '72' : '82') .'px;') . '
                                            transform: rotate(180deg);" src="' . $VisionPanelGlazingImageStructure . '" alt="">


                                        </div>
                                    </div>';

                        $DoorFrameImage .= '   <div style="position: relative;position: absolute;top: 7px;left:475px;"><img style="width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '158' : '170') .'px;
                                        position: relative;
                                        bottom: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '5') .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-32' : '-31') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? 'height:65px;' : '') .
        '" src="' . $FrameImageStructureRight . '" alt="">
                                    </div>';
                    } else {

                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                        $DoorFrameImage .= '<img style="width: 575px;
                        position: relative;
                        top: -17px;
                        left: 11px;height: 108px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    $DoorFrameImage .= '
                                <div style="position: relative;position: absolute;top: 7px;left:642px;">';

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            !empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : '-35.5') : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 40px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : '-35.5') : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 30px;"></div>

                                        <div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : '-35.5') : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 57px;"></div>';
                        }
                    }
                    
                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }

                    $DoorFrameImage .= '<div style="position: absolute;top: 21px;right: -27px;">
                                        <img style="width: 77px;
                                        margin-top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-53' : '-1') .'px;" src="' .  $FrameTypeRight . '" alt="">
                                    </div>
                                </div>
                            ';

                    if($sidelight !== "" && $tt->SideLight2 == 'Yes'){

                        $DoorFrameImage .= '<div style="position: absolute;top: 23px;left: 912px;">
                        <div style="
                           width: 0px;
                           margin: 0 auto;
                           position: relative;
                           margin-left: -66px;
                           top: -131px;
                           left: -119px;
                           ">
                           <img style="
                              z-index: 999;
                              position: absolute;
                              width: 69px;
                              height: 71px;
                              margin-left: -26px;
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;'
                                :
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;')
                            .'
                              " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-49' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-252' : '-265').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '77').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '212' : '194').'px;" alt="" src="'.$FrameTypeCommon.'">
                        </div>
                        <div style="
                           width: 0px;
                           margin: 0 auto;
                           position: relative;
                           margin-left: -79px;
                           top: 2px;
                           left: -4px;
                           transform: rotate(180deg);
                           ">
                           <img style="
                              z-index: 999;
                              position: absolute;
                              margin-top: 86px;
                              width: 69px;
                              height: 71px;
                              margin-left: -8px;
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;')
                            .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-48' : '7').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-84' : '-87').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : '194').'px;" alt="" src="'.$FrameTypeCommon.'"
                              >
                        </div>
                       </div>

                      </div>';

                    }

                    break;


                case "DD":
                    // $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding: 10px 30px; position: relative;margin-left: '.$frameImageLeftMargin.'px;">
                            <div style="position: relative;  top: 12px;">';



                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 18px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 10px;"></div>
                                    <div class="'.$redstripLeftCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 25px;"></div>';
                        }
                    }



                    // ----------------Left-------------------

                    if($sidelight !== "" && $tt->SideLight1 == 'Yes'){

                        $DoorFrameImage .= '<div style="
                                width: 0px;
                                margin: 0 auto;
                                position: relative;
                                margin-left: -66px;
                                top: -131px;
                                left: -119px;
                                ">

                                <img style="
                                z-index: 999;
                                position: absolute;
                                '.
                                (($tt->FireRating == 'FD30') ?
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                :
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                 .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />


                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-19' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-233' : '-222').'px;">';
                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
                            </div>

                            <div style="
                            width: 0px;
                            margin: 0 auto;
                            position: relative;
                            margin-left: -79px;
                            top: 2px;
                            left: 13px;
                            transform: rotate(180deg);
                            ">

                                <img style="
                                    z-index: 999;
                                    position: absolute;
                                    margin-top: 86px;
                                    '.
                                    (($tt->FireRating == 'FD30') ?
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;'
                                    :
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;')
                                    .'
                                    " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >





                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-74' : '-66').'px;">';

                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
                            </div>
                        ';

                    }


                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4')) .'px; left: -40px;">
                                    <img style="width: '. (
                                                ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px; alt="" src="' . $FrameTypeLeft . '">
                                </div>
                            </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '
                                <div style="position: relative;  top: 12px;">
                                    <img style="width: 97px;
                                        position: relative;
                                        top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10')) .'px;
                                        left:-5px " alt="" src="' . $FrameImageStructureLeftLeaf1 . '">
                                </div>
                                <div style="width:265px; position: relative;">
                                    <div style="width: 0px;
                                    margin: 0 auto;
                                    position: relative;
                                    margin-left: -66px;
                                    top: -185px;
                                    left: 258px;">
                                        <img style="z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px; margin-left:  '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100' : '-100') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-106')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '172' : '131')) .'px;'
                                                 :
                                                 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100.5' : '-103.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-104.5' : '-103.5')) .'px;
                                        height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174.5' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '171.5' : '131.5')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                        <img style="position: absolute;
                                        top: 120px;
                                        transform: rotate(90deg);
                                        z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'left: -99px;width: 17px;' : 'left: -97px;width: 14px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                    </div>

                                    <div style="width: 200px; margin-top: 120px; position: relative; top:-354px; left:220px;">

                                        <img style="
                                        transform: rotate(180deg);
                                        z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-62' : '-62')) .'px;
                                        margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '16.5' : '8') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '12.5' : '1')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '41') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;'
                                                :
                                                'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-65' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-64')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '18' : '7') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '13' : '0')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                    </div>
                                </div>

                                <div style="position: absolute;
                                    top: 8px;
                                    left: 290px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;
                                margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '53') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') .'px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class=""  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 23px;"></div>
                                            <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 40px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5')) .'px;
                                        right: 37.5px;" alt="" src="' . $FrameImageStructureRightLeaf1 . '">
                                </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 18px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 13px;"></div>
                                            <div class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 24px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="
                                    width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '105' : '323')  .'px;
                                    position: relative;
                                    top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '6' : '-10')  .'px;
                                    left: -5.5px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    if ($tt->Leaf2VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '78' : '95')  .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5')  .'px;
                                            right:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56.5' : '37.5')  .'px;" alt="" src="' . $FrameImageStructureLeftLeaf2 . '">
                                    </div>

                                    <div style="position: absolute;top: 78px;left: 286px;">
                                        <div style="width: 0px;
                                            margin: 0 auto;
                                            position: relative;
                                            margin-left: -66px;
                                            top: -185px;
                                            left: 228px;">

                                            <img style="
                                                z-index: 999;
                                                ' . (($tt->FireRating == 'FD30') ? 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;'
                                                 :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


                                            <img style="position: absolute;
                                                z-index: -1;
                                                transform: rotate(90deg);height:40px;' . (($tt->FireRating == 'FD30') ? 'width: 17px; right: -14px;top: 117px;' : 'width: 14px;right: -12.9px;top: 117.3px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                        </div>
                                        <div style="width: 200px;
                                            margin-top: 120px;
                                            position: relative;
                                            top: -408px;
                                            left: 190px;">

                                            <img style="
                                                z-index: 999;
                                                transform: rotate(180deg);
                                                ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;'
                                                :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


                                            <img style="position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px;right: 103px;' : 'top: 77px;right: 104px;width: 14px;') . 'z-index: -1;
                                                transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                        </div>
                                    </div>
                                    <div style="position: relative;  position: absolute; top: 8px; left: 630px;">
                                        <img style="width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '136' : '95') .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5') .'px;
                                            right: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '55') .'px; alt="" src="' . $FrameImageStructureRightLeaf2 . '">
                                    </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4')) .'px;;
                                        right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35')) .'px;" alt="" src="' . $FullBlock . '">
                                    </div>';
                    }

                    $DoorFrameImage .= '
                                    <div style="position: relative;  position: absolute; top: 8px; left: 722px;">';





                            if (($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') && in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):'-51').'px;margin-top: 24px;"></div>
                                            <div class="'.$redstripLeftCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):'-51').'px;margin-top: 37px;"></div>';
                            }

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            if($tt->Leaf2VisionPanel == 'Yes'){
                            $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-64':'-73') : '-50') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '33') .'px;"></div>';
                            }
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            if($tt->Leaf2VisionPanel =! 'Yes'){


                        $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 23px;"></div>
                                    <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 40px;"></div>';
                                }
                        }
                    }

                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }
                    
                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18')) .'px;
                                                right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '32':'32') : '20') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '33':'33') : '20')) .'px;">
                                            <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px;" alt="" src="' . $FrameTypeRight . '">
                                        </div>';

                    // ----------------Right-------------------

                    if($sidelight !== "" && $tt->SideLight2 == 'Yes'){

                        $DoorFrameImage .= '<div style="position:relative; right:-191px; bottom:-14px;"><div style="
                                width: 0px;
                                margin: 0 auto;
                                position: relative;
                                margin-left: -66px;
                                top: -131px;
                                left: -119px;
                                ">

                                <img style="
                                z-index: 999;
                                position: absolute;
                                '.
                                    (($tt->FireRating == 'FD30') ?
                                    'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                    :
                                   'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                    .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />





                            </div>

                            <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel =! 'Yes')? '-456' : '-229') : '-222').'px;">';
                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
                            </div>

                            <div style="
                                width: 0px;
                                margin: 0 auto;
                                position: relative;
                                margin-left: -79px;
                                top: 6px;
                                left: 13px;
                                transform: rotate(180deg);
                                ">

                                <img style="
                                z-index: 999;
                                margin-top: 86px;
                                position: absolute;
                                '.
                                    (($tt->FireRating == 'FD30') ?
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;'
                                    :
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;')
                                    .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >




                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:  '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-73' : '-66').'px;">';
                            $DoorFrameImage .= '<img style="width: 46px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
                            </div>
                            </div>
                        ';

                    }

                break;

            }

            $QuotationGenerationId = null;
            if (!empty($quotaion->QuotationGenerationId)) {
                $QuotationGenerationId = $quotaion->QuotationGenerationId;
            }
            
            $configurationItem = $quotaion->configurableitems;
            if (!empty($quotaion->configurableitems)) {
                $configurationItem = $quotaion->configurableitems;
            }
            
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

            $CstCompanyAddressLine1 = '';
            if (!empty($customerContact)) {
                $customer = Customer::where(['UserId' => $quotaion->MainContractorId])->first();
                $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
            }

            $SalesContact = 'N/A';
            if (!empty($quotaion->SalesContact)) {
                $SalesContact = $quotaion->SalesContact;
            }

            if (!empty($tt->SvgImage)) {
                $svgFile = strpos($tt->SvgImage, '.png') !== false ? URL('/') . '/uploads/files/' . $tt->SvgImage : $tt->SvgImage;
            } else {
                $svgFile = URL('/') . '/uploads/files/no_image_prod.jpg';
            }
            
            $elevTbl .=
                '
                <div id="headText">
                    <b>Elevation Drawing</b>
                </div>
                <div id="main">
                    <div id="section-left">
                        <table id="NoBorder">
                            <tr>
                                <td colspan="2">
                                    <table id="WithBorder" class="tbl1">
                                        <tbody>
                                            <tr>
                                                <td class="marImg" rowspan="2">
                                                    <span>';
            if (!empty($comapnyDetail->ComplogoBase64)) {
                $elevTbl .=
                    '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevTbl .= Base64Image('defaultImg');
            }
            
            $elevTbl .=
                '</span>
                                                </td>
                                                <td class="tbl_color"><span>Ref</span></td>
                                                <td colspan="3"><span>' . $QuotationGenerationId . '</span></td>
                                                <td class="tbl_color"><span>Project</span></td>
                                                <td><span>' . $ProjectName . '</span></td>
                                                <td class="tbl_color"><span>Prepared By</span></td>
                                                <td><span>' . $Username . '</span></td>
                                            </tr>
                                            <tr>
                                                <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>
                                                <td style="width:20px;"><span>' . $version . '</span></td>
                                                <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                                <td><span >' . date('Y-m-d') . '</span></td>
                                                <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                                <td><span>' . $CstCompanyAddressLine1 . '</span></td>
                                                <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Sales Contact</span></td>
                                                <td><span>' . $SalesContact . '</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="WithBorder" class="tbl1 dashedborder">
                                        <tbody>
                                            <tr>
                                                <td class="marImg" rowspan="2">
                                                    <span>';
            if (!empty($comapnyDetail->ComplogoBase64)) {
                $elevTbl .=
                    '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevTbl .= Base64Image('defaultImg');
            }
            
            $elevTbl .=
                '</span>
                                                </td>
                                                <td class="tbl_color"><span>IO No</span></td>
                                                <td>' . $project->ioNumberOne . '</td>
                                                <td>' . $project->ioNumberTwo . '</td>
                                                <td>' . $project->ioNumberThree . '</td>
                                                <td class="tbl_color"><span>Frame Po</span></td>
                                                <td>' . $project->framePoOne . '</td>
                                                <td>' . $project->framePoTwo . '</td>
                                                <td>' . $project->framePoThree . '</td>
                                                <td class="tbl_color"><span>Ironmongery Po</span></td>
                                                <td>' . $project->ironmongeryPoOne . '</td>
                                                <td>' . $project->ironmongeryPoTwo . '</td>
                                                <td>' . $project->ironmongeryPoThree . '</td>
                                            </tr>
                                            <tr>
                                                <td class="tbl_color"><span>Door Po</span></td>
                                                <td>' . $project->doorPoOne . '</td>
                                                <td>' . $project->doorPoTwo . '</td>
                                                <td>' . $project->doorPoThree . '</td>
                                                <td class="tbl_color"><span>Glass Po</span></td>
                                                <td>' . $project->glassPoOne . '</td>
                                                <td>' . $project->glassPoTwo . '</td>
                                                <td>' . $project->glassPoThree . '</td>
                                                <td class="tbl_color"><span>Intumescent Po</span></td>
                                                <td>' . $project->intumescentPoOne . '</td>
                                                <td>' . $project->intumescentPoTwo . '</td>
                                                <td>' . $project->intumescentPoThree . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
            $elevTbl .= '<tr>';

            $ConfigurableItems = "Streboard";

            $SelectedConfigurableItems = ConfigurableItems::find($configurationItem);
            if ($SelectedConfigurableItems != null) {
                $ConfigurableItems = $SelectedConfigurableItems->name;
            }


            $GlassType = "";

            $SelectedGlassType =  GlassType::where($configurationDoor,$configurationItem)
            ->where($fireRatingDoor,$FireRatingActualValue)
            ->where("Key",$tt->GlassType)->first();

            if ($SelectedGlassType != null) {
                $GlassType = $SelectedGlassType->GlassType;
            }

            $GlazingBeadSpecies = "";

            $SelectedGlazingBeadSpecies = LippingSpecies::find($tt->GlazingBeadSpecies);
            if ($SelectedGlazingBeadSpecies != null) {
                $GlazingBeadSpecies = $SelectedGlazingBeadSpecies->SpeciesName;
            }

            if ($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes") {

                $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
                                    <p class="visionpanel_t1">' . $GlassType . '</p>
                                    <p class="visionpanel_t2">' . $tt->glazingBeadsFixingDetail . '</p>
                                    <p class="visionpanel_t3">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                    <p class="visionpanel_t4">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
                                    <p class="visionpanel_t5">' . $tt->LeafThickness . '</p>
                                    <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
                                </td>';
            }

            $IsLeafEnabled = $tt->Leaf1VisionPanel == "Yes" ? 'style="width:80%;"' : 'colspan="2"';

            $elevTbl .= '<td ' . $IsLeafEnabled . '>
                <div class="doorImgBox">
                    <!--<img src="' . URL('/') . '/uploads/files/' . $svgFile . '" class="doorImg">-->
                    <img src="' . $svgFile . '" class="doorImg" style="">
                </div>
            </td>
            </tr>';
            if (isset($DoorFrameImage)) :
                $FrameMaterial = 'N/A';

                if (!empty($tt->FrameMaterial) && !in_array($tt->FrameMaterial, ["MDF", "Softwood", "Hardwood"])) {
                    $SelectedFrameMaterial = LippingSpecies::find($tt->FrameMaterial);
                    if ($SelectedFrameMaterial != null) {
                        $FrameMaterial = $SelectedFrameMaterial->SpeciesName;
                        $FrameMaterial .= "<br>-" . (($SelectedFrameMaterial->MinValue > 0) ? $SelectedFrameMaterial->MinValue . "x" : "") . $SelectedFrameMaterial->MaxValues . " Kg/m3";
                    } else {
                        $SelectedFrameMaterial = LippingSpecies::where("SpeciesName", $tt->FrameMaterial)->first();
                        if ($SelectedFrameMaterial != null) {
                            $FrameMaterial = $SelectedFrameMaterial->SpeciesName;
                            $FrameMaterial .= "<br>-" . (($SelectedFrameMaterial->MinValue > 0) ? $SelectedFrameMaterial->MinValue . "x" : "") . $SelectedFrameMaterial->MaxValues . " Kg/m3";
                        }
                    }
                }

                $maxIN = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);
                $FrameTypeWidth = $tt->FrameWidth;
                $FrameTypeHeight = $tt->FrameHeight;
                if (!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') {
                    $FrameTypeWidth = $tt->RebatedWidth;
                    $FrameTypeHeight = $tt->RebatedHeight;
                } elseif (!empty($tt->FrameType) && $tt->FrameType == 'Plant_on_Stop') {
                    $FrameTypeWidth = $tt->PlantonStopWidth;
                    $FrameTypeHeight = $tt->PlantonStopHeight;
                } elseif (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                    $FrameTypeWidth = $tt->ScallopedWidth;
                    $FrameTypeHeight = $tt->ScallopedHeight;
                }

                switch ($tt->DoorsetType) {

                    case "SD":
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata_sd" colspan="2" style="">
                                    <p class="frame_dd_t1_sd_' . $tt->FrameType . ' frame_dd_t1_sd_'.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2_sd_' . $tt->FrameType . ' frame_dd_t2_sd_'.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3_sd_' . $tt->FrameType . ' frame_dd_t3_sd_'.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4_sd_' . $tt->FrameType . ' frame_dd_t4_sd_'.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5_sd_' . $tt->FrameType . ' frame_dd_t5_sd_'.$sidelight.'">' . $tt->LeafThickness . '</p>';
                        }
                        
                        $elevTbl .= '<!--  <div class="arrow-strat"></div>
                                        <p class="frame_sd_t1">-' . $FrameMaterial . '</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_sd_t2">-' . $ConfigurableItems . '<br>-' . $tt->LeafThickness . 'mm</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_sd_t3">-' . $GlazingBeadSpecies . '<br>-' . $tt->glazingBeadsWidth . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_sd_t4">-' . $tt->GlassThickness . 'mm ' . $GlassType . '</p>
                                        <p class="frame_sd_z1">' . $tt->LeafWidth1 . '</p>
                                        <p class="frame_sd_z2">' . $tt->FrameWidth . '</p>
                                        <p class="frame_sd_z3">' . $tt->Leaf1VPWidth . '</p>
                                        <p class="frame_sd_z4">' . $maxIN . '</p>
                                        <p class="frame_sd_z5">' . $tt->DistanceFromTheEdgeOfDoor . '</p> -->

                                        ' . $DoorFrameImage . '</td></tr>';
                        break;

                    case "DD":

                        // dd($tt);
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata" colspan="2" style="">

                                    <p class="frame_dd_t1 frame_dd_t1_' . $tt->FrameType.' '.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2 frame_dd_t2_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3 frame_dd_t3_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4 frame_dd_t4_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5 frame_dd_t5_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->LeafThickness . '</p>';
                        }
                        
                        $elevTbl .= '<!-- <div class="arrow-strat"></div>  <p class="frame_sd_t1">' . $tt->FrameDepth . '</p>
                                        <p class="frame_sd_t2">' . $tt->FrameThickness . '</p>
                                        <p class="frame_dd_t1">-' . $FrameMaterial . '</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_dd_t2 ">-' . $ConfigurableItems . '<br>-' . $tt->LeafThickness . 'mm</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_dd_t3 arrow">-' . $GlazingBeadSpecies . '<br>-' . $tt->glazingBeadsWidth . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_dd_t4">-' . $tt->GlassThickness . 'mm ' . $GlassType . '</p>
                                        <div class="arrow-strat"></div>
                                        <p class="frame_dd_t5">-' . $tt->GAP . 'mm GAP</p>
                                        <p class="frame_sd_DD1">' . $tt->FrameWidth . '</p>
                                        <p class="frame_sd_DD2">' . $tt->DistanceFromTheEdgeOfDoor . '</p>
                                        <p class="frame_sd_DD3">' . $tt->DistanceFromTheEdgeOfDoorforLeaf2 . '</p>
                                        <p class="frame_sd_DD4">' . $tt->Leaf1VPWidth . '</p>
                                        <p class="frame_sd_DD5">' . $tt->LeafWidth1 . '</p>
                                        <p class="frame_sd_DD6">' . $tt->GAP . '</p>  -->

                                        ' . $DoorFrameImage . '</td>
                                        </tr>';
                        break;
                }
            endif;
            
            // }




            $ExtLinerValue = '';
            if (!empty($tt->ExtLinerValue)) {
                $ExtLinerValue = $tt->ExtLinerValue;
            }
            
            $ExtLinerThickness = '';
            if (!empty($tt->ExtLinerThickness)) {
                $ExtLinerThickness = $tt->ExtLinerThickness . "mm";
            }

            if (empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                $ExtLinerSizeForDoorDetailsTable = "N/A";
            } elseif (empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                $ExtLinerSizeForDoorDetailsTable = 'N/A x ' . $ExtLinerThickness;
            } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue . ' x N/A';
            } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue . ' x ' . $ExtLinerThickness;
            }

            $SpeciesName = 'N/A';
            if (!empty($tt->LippingSpecies)) {
                $ls = LippingSpecies::where('id', $tt->LippingSpecies)->first();
                $SpeciesName = $ls->SpeciesName;
            }
            
            $intumescentSealType = 'N/A';
            if (!empty($tt->IntumescentLeapingSealType)) {
                $intumescentSealType = IntumescentSealType($configurationItem, $tt->IntumescentLeapingSealType);
            }

            $DoorLeafFinish = "N/A";
            if (!empty($tt->DoorLeafFinish)) {
                $dlf = DoorLeafFinish($configurationItem, $tt->DoorLeafFinish);
                $DoorLeafFinish = empty($tt->SheenLevel) ? $dlf : $dlf . ' - ' . $tt->SheenLevel . ' Sheen';
            }
            
            $DoorLeafFinishColor = '';
            if (!empty($tt->DoorLeafFinishColor)) {
                $DoorLeafFinishColor = ' + ' . $tt->DoorLeafFinishColor;
            }
            
            $DoorLeafFacing = "N/A";
            if (!empty($tt->DoorLeafFacing)) {
                $DoorLeafFacing = DoorLeafFacing($configurationItem, $tt->DoorLeafFacing, $tt->DoorLeafFacingValue);
            }

            $FrameTypeForDoorDetailsTable = 'N/A';
            if (!empty($tt->FrameType)) {
                $FrameTypeForDoorDetailsTable = FrameType($configurationItem, $tt->FrameType);
            }

            $FrameFinishForDoorDetailsTable = 'N/A';
            if (!empty($tt->FrameFinish)) {
                $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem, $tt->FrameFinish, $tt->FrameFinishColor);
            }

            $LippingType = "N/A";
            if (!empty($tt->LippingType)) {
                $SelectedLippingType = Option::where("configurableitems", $configurationItem)
                    ->where("OptionSlug", "lipping_type")
                    ->where("OptionKey", $tt->LippingType)->first();
                if ($SelectedLippingType != null) {
                    $LippingType = $SelectedLippingType->OptionValue;
                }
            }


            $GlassTypeForDoorDetailsTable = "N/A";
            if (!empty($tt->GlassType)) {
                $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem, $FireRatingActualValue, $tt->GlassType, $tt->GlassThickness);
            }
            
            $OPGlassTypeForDoorDetailsTable = "N/A";
            if (!empty($tt->OPGlassType)) {
                $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem, $FireRatingActualValue, $tt->OPGlassType);
            }
            
            $ArchitraveFinishForDoorDetailsTable = "N/A";
            if (!empty($tt->ArchitraveFinish)) {
                $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem, $tt->ArchitraveFinish, $tt->FrameFinishColor);
            }

            $GlassIntegrity = 'N/A';
            if (!empty($tt->GlassIntegrity)) {
                if($FireRatingActualValue == 'NFR'){
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }
                else{
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }
                
                $GlassIntegrity = $gi->OptionValue;
            }
            
            $OPGlazingBeads = 'N/A';
            if (!empty($tt->OPGlazingBeads)) {
                $opgb = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", "fan_light_glazing_beads")
                    ->where("OptionKey", $tt->OPGlazingBeads)
                    ->first();

                $OPGlazingBeads = $opgb ? $opgb->OptionValue : null;
            }

            $SLBeadingType = 'N/A';
            if (!empty($tt->BeadingType)) {
                $bt = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", "side_light_glazing_beads")
                    ->where("OptionKey", $tt->BeadingType)
                    ->first();

                $SLBeadingType = $bt ? $bt->OptionValue : null;
            }

            $glazingSystems = 'N/A';
            if (!empty($tt->GlazingSystems)) {
                 $gs = GlazingSystem::join('selected_glazing_system','glazing_system.id','selected_glazing_system.glazingId')->where('selected_glazing_system.userId', Auth::user()->id)->where('glazing_system.'.$configurationDoor,$tt->configurableitems)->where('glazing_system.Key',$tt->GlazingSystems)->first();
                $glazingSystems = @$gs->GlazingSystem;
            }

            if ($tt->SwingType == 'SA') {
                $SwingType = 'Single Acting';
            } elseif ($tt->SwingType == 'DA') {
                $SwingType = 'Double Acting';
            } else {
                $SwingType = '';
            }

            // Under the row ‘Decorative Groves’ this should show the width x depth. Example 5mm wide x 2mm deep
            if (!empty($tt->DecorativeGroves)) {
                $GrooveWidth = empty($tt->GrooveWidth) ? 'N/A' : $tt->GrooveWidth . 'mm wide';

                $GrooveDepth = empty($tt->GrooveDepth) ? 'N/A' : $tt->GrooveDepth . 'mm deep';

                $DecorativeGroves = empty($tt->GrooveWidth) && empty($tt->GrooveDepth) ? 'N/A' : $GrooveWidth . ' x ' . $GrooveDepth;
            } else {
                $DecorativeGroves = 'N/A';
            }

            $leafWidth1 = empty($tt->LeafWidth1) ? 'N/A' : $tt->LeafWidth1;

            $leafWidth2 = empty($tt->LeafWidth2) ? 'N/A' : $tt->LeafWidth2;

            $LeafHeight = empty($tt->LeafHeight) ? 'N/A' : $tt->LeafHeight;

            $LeafThickness = empty($tt->LeafThickness) ? 'N/A' : $tt->LeafThickness;

            $FrameDepth = empty($tt->FrameDepth) ? 'N/A' : $tt->FrameDepth;
            $IronmongerySet = empty($tt->IronmongeryID) ? 'N/A' : IronmongerySetName($tt->IronmongeryID);

            $rWdBRating = 'N/A';
            if (!empty($tt->rWdBRating)) {
                $rWdBRating = $tt->rWdBRating;
            }



            $intumescentSealArrangement = 'N/A';
            if (!empty($tt->IntumescentLeapingSealArrangement)) {
                $Intumescentseals = SettingIntumescentSeals2::where('id', $tt->IntumescentLeapingSealArrangement)->first();
                if($Intumescentseals){
                    $intumescentSealArrangement =  $Intumescentseals->brand . ' - ' . $Intumescentseals->intumescentSeals;
                }
            }

            $IntumescentLeapingSealColor = 'N/A';
            if (!empty($tt->IntumescentLeapingSealColor)) {
                $IntumescentLeapingSealColor = $tt->IntumescentLeapingSealColor;
            }

            $ArchitraveMaterial = 'N/A';
            if (!empty($tt->ArchitraveMaterial)) {
                if ($tt->ArchitraveMaterial == 'Softwood' || $tt->ArchitraveMaterial == 'MDF' || $tt->ArchitraveMaterial == 'Hardwood') {
                    $ArchitraveMaterial = 'N/A';
                } else {
                    $ls = LippingSpecies::where('id', $tt->ArchitraveMaterial)->first();
                    $ArchitraveMaterial = $ls->SpeciesName;
                }
            }
            
            $ArchitraveSetQty = 'N/A';
            if (!empty($tt->ArchitraveSetQty)) {
                $ArchitraveSetQty = $tt->ArchitraveSetQty;
            }
            
            $ArchitraveWidth = 'N/A';
            if (!empty($tt->ArchitraveWidth)) {
                $ArchitraveWidth = $tt->ArchitraveWidth;
            }
            
            $ArchitraveDepth = 'N/A';
            if (!empty($tt->ArchitraveDepth)) {
                $ArchitraveDepth = $tt->ArchitraveDepth;
            }
            
            $ArchitraveHeight = 'N/A';
            if (!empty($tt->ArchitraveHeight)) {
                $ArchitraveHeight = $tt->ArchitraveHeight;
            }



            // Add a new section called 'Side Screen Section' SL1 Glass Type , Beading Type and Glazing Bead Species.
            $sl1glasstype = 'N/A';
            if (!empty($tt->SideLight1GlassType)) {
                $op = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing', function ($join) use ($id): void {
                    $join->on('overpanel_glass_glazing.id', '=', 'selected_overpanel_glass_glazing.glass_glazing_id')
                        ->where('selected_overpanel_glass_glazing.editBy', '=', $id);
                })->where('overpanel_glass_glazing.'.$configurationDoor,$tt->configurableitems)->where('overpanel_glass_glazing.Key',$tt->SideLight1GlassType)->first();
                $sl1glasstype = $op->GlassType;
            }

            $beadingtype = 'N/A';
            if (!empty($tt->SideLight2BeadingType)) {
                $op2 = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => 'leaf1_glazing_beads', 'OptionKey' => $tt->SideLight2BeadingType])->first();
                $beadingtype = ($op2->OptionValue)??"";
            }

            $glazingbeadspecies = 'N/A';
            if (!empty($tt->SL1GlazingBeadSpecies)) {
                $ls = LippingSpecies::where('id', $tt->SL1GlazingBeadSpecies)->first();
                if (!empty($ls->SpeciesName)) {
                    $glazingbeadspecies = $ls->SpeciesName;
                }
            }

            $VPBeadingType = 'N/A';
            if (!empty($tt->GlazingBeads)) {
                $VPBeadingType = VPBeadingType($configurationItem, 'leaf1_glazing_beads', $tt->GlazingBeads);
            }

            $elevTbl .= '</table>

                    </div>
                    <div id="section-right">
                        <table id="WithBorder" class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                                    <td class="dicription_blank"><b>Type ' . $tt->DoorType . '</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Type</td>
                                    <td class="dicription_blank">' . $tt->DoorType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">' . $fire . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Swing Type</td>
                                    <td class="dicription_blank">' . $SwingType . ' ' . $tt->SwingType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Open Inwards</td>
                                    <td class="dicription_blank">' . $tt->OpensInwards . '</td>
                                </tr>';
                if($tt->FrameOnOff != 1){
                    $elevTbl .=  '<tr>
                                    <td class="dicription_grey">Handing</td>
                                    <td class="dicription_blank">' . $tt->Handing . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Undercut</td>
                                    <td class="dicription_blank">' . $tt->Undercut . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Thickness</td>
                                    <td class="dicription_blank">' . $tt->FrameThickness . '</td>
                                </tr>';
                    }

                    $elevTbl .= '<tr>
                                    <td class="dicription_grey">Ironmongery Set</td>
                                    <td class="dicription_blank">' . $IronmongerySet . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>';
            if($tt->FrameOnOff != 1){
                $elevTbl .=  '  <tr>
                                    <td class="dicription_grey">S.O. Width</td>
                                    <td class="dicription_blank">' . $tt->SOWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Height</td>
                                    <td class="dicription_blank">' . $tt->SOHeight . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Depth</td>
                                    <td class="dicription_blank">' . $tt->SOWallThick . '</td>
                                </tr>';
            }
            
            $elevTbl .=  '  <tr>
                                    <td class="dicription_grey">Door leaf Facing</td>
                                    <td class="dicription_blank">' . $DoorLeafFacing . '</td>
                                </tr>';
        if($quotaion->configurableitems == 4){
            $elevTbl .=         '<tr>
                                    <td class="dicription_grey">Leaf Type</td>
                                    <td class="dicription_blank">' . $tt->LeafConstruction . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Dimension</td>
                                    <td class="dicription_blank">' . $tt->DoorDimensionsCode . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $tt->groovesNumber . '</td>
                                </tr>';
        }else{
            $elevTbl .=         '<tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">' . $DoorLeafFinish . $DoorLeafFinishColor . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $DecorativeGroves . '</td>
                                </tr>';
        }
        
            $elevTbl .=         '
                                <tr>
                                    <td class="dicription_grey">Door Leaf Width 1</td>
                                    <td class="dicription_blank">' . $leafWidth1 . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Width 2</td>
                                    <td class="dicription_blank">' . $leafWidth2 . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Height</td>
                                    <td class="dicription_blank">' . $LeafHeight . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Thickness</td>
                                    <td class="dicription_blank">' . $LeafThickness . '</td>
                                </tr>
                            </tbody>
                        </table>';
            if($tt->FrameOnOff != 1){
                $elevTbl .=  '<table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Frame</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Frame Material</td>
                                        <td class="dicription_blank">' . $frameMaterial . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Type</td>
                                        <td class="dicription_blank">' . $FrameTypeForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Width</td>
                                        <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Height</td>
                                        <td class="dicription_blank">' . $tt->FrameHeight . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Depth</td>
                                        <td class="dicription_blank">' . $FrameDepth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Finish</td>
                                        <td class="dicription_blank">' . $FrameFinishForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Ext-Liner</td>
                                        <td class="dicription_blank">' . $tt->ExtLiner . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Ext-Liner Size</td>
                                        <td class="dicription_blank">' . $ExtLinerSizeForDoorDetailsTable . '</td>
                                    </tr>
                                </tbody>
                            </table>';
            }
            
            $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Lipping And Intumescent</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Type</td>
                                    <td class="dicription_blank">' . $intumescentSealType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Lipping Species</td>
                                    <td class="dicription_blank">' . $SpeciesName . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Intumescent Seal Brand</td>
                                    <td class="dicription_blank">' . $intumescentSealArrangement . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Intumescent Seal Color</td>
                                    <td class="dicription_blank">' . $IntumescentLeapingSealColor . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Vision Panel</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Integrity</td>
                                    <td class="dicription_blank">' . $GlassIntegrity . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Type + Thickness</td>
                                    <td class="dicription_blank">' . $GlassTypeForDoorDetailsTable . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">' . $GlazingBeadSpecies . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Beading Type</td>
                                    <td class="dicription_blank">' . $VPBeadingType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing System</td>
                                    <td class="dicription_blank">' . $glazingSystems . '</td>
                                </tr>
                            </tbody>
                        </table>';
                if($tt->FrameOnOff != 1){
                            $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Overpanel/Fanlight Section</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glass Type</td>
                                    <td class="dicription_blank">' . $OPGlassTypeForDoorDetailsTable . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glazing Beads</td>
                                    <td class="dicription_blank">' . $OPGlazingBeads . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glazing Bead Species</td>
                                    <td class="dicription_blank">' . $OPGlazingBeadSpecies . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Side Screen Section</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SL1 Glass Type</td>
                                    <td class="dicription_blank">' . $sl1glasstype . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Beading Type</td>
                                    <td class="dicription_blank">' . $SLBeadingType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">' . $glazingbeadspecies . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Architrave</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Material</td>
                                    <td class="dicription_blank">' . $ArchitraveMaterial . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Finish</td>
                                    <td class="dicription_blank">' . $ArchitraveFinishForDoorDetailsTable . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Set Qty</td>
                                    <td class="dicription_blank">' . $ArchitraveSetQty . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Width</td>
                                    <td class="dicription_blank">' . $ArchitraveWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Depth</td>
                                    <td class="dicription_blank">' . $ArchitraveDepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Height</td>
                                    <td class="dicription_blank">' . $ArchitraveHeight . '</td>
                                </tr>
                            </tbody>
                        </table>';
                        }
                
                        $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Accoustics</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Rating</td>
                                    <td class="dicription_blank">' . $rWdBRating . '</td>
                                </tr>
                            </tbody>
                        </table>';
                        if($tt->FrameOnOff != 1){
                            $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Special Feature</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Special Feature Refs</td>
                                    <td class="dicription_blank">' . $tt->SpecialFeatureRefs . '</td>
                                </tr>
                            </tbody>
                        </table>';
                        }
                        
                        $elevTbl .=  '</div></div>
                    <div id="footer">
                        <h3><b>Total Doorsets: ' . $countDoorNumber . ',Door No-' . $doorNo . '</b></h3>

                    </div>
                ';

            if ($PageBreakCount < $TotalItems || $PageBreakCounts == $TotalItems) {
                $elevTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;

            $ItemsPerPage = 15;
            $elevTbl .= ' <div class="tbl_prn">
            <div style="margin:0 auto;"><h3 style="text-align:center;">Quality Control </h3></div>
            <div class="fr_d_tbl doorImgBox" style="display:flex; justify-content:center;">
            <table id="NoBorder" style="margin-top:1rem;" class="mt-4">
                <tr>
                    <td colspan="2">
                        <table id="WithBorder" class="tbl1">
                            <tbody>
                                <tr>
                                    <td class="marImg" rowspan="2">
                                        <span>';
            if (!empty($comapnyDetail->ComplogoBase64)) {
                $elevTbl .= '<img src="' . htmlspecialchars($comapnyDetail->ComplogoBase64) . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevTbl .= Base64Image('defaultImg');
            }
            
            $elevTbl .= '</span>
                                    </td>
                                    <td class="tbl_color"><span>Ref</span></td>
                                    <td colspan="3"><span>' . htmlspecialchars($QuotationGenerationId) . '</span></td>
                                    <td class="tbl_color"><span>Project</span></td>
                                    <td><span>' . htmlspecialchars($ProjectName) . '</span></td>
                                    <td class="tbl_color"><span>Prepared By</span></td>
                                    <td><span>' . htmlspecialchars($Username) . '</span></td>
                                </tr>
                                <tr>
                                    <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>
                                    <td style="width:20px;"><span>' . htmlspecialchars($version) . '</span></td>
                                    <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                    <td><span>' . date('Y-m-d') . '</span></td>
                                    <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                    <td><span>' . htmlspecialchars($CstCompanyAddressLine1) . '</span></td>
                                    <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Sales Contact</span></td>
                                    <td><span>' . htmlspecialchars($SalesContact) . '</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            </div>
            <div class="fr_d_tbl" style=" margin: 0 auto;">
            <table id="WithBorder" style="margin-top:1rem; width:500px; margin:0 auto 40px;" class="tbl2  mt-4">
                <tbody>
                    <tr>
                        <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                        <td class="dicription_blank"><b>Type ' . htmlspecialchars($tt->DoorType) . '</b></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="fr_d_tbl doorImgBox" style="display:flex; justify-content:center;">
            <table style="background:#fff; margin-top:1rem;border-collapse: collapse;" class="fir-dr-tbl mytableclass mt-3">
                <thead>
                    <tr>
                        <th>Door / Screen No</th>
                        <th>Quality Check- CNC</th>
                        <th>Quality Check- VP</th>
                        <th>Quality Check- Assembly</th>
                        <th>Door Plug</th>
                        <th>Frame Plug</th>
                        <th>Intumescent</th>
                        <th>VP Glass</th>
                        <th>VP Glazing System</th>
                        <th>FL/SL Glass</th>
                        <th>FL/SL Glazing System</th>
                        <th>Timber Species</th>
                    </tr>
                </thead>

                <tbody>';
                foreach ($DoorNumber as $bb) {
                    $elevTbl .=  '<tr>
                        <td>' . $bb->doorNumber . '</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
                }
                
                $elevTbl .= '</tbody>
            </table>
            </div>
            </div>';
            if ($PageBreakCounts < $TotalItems) {
                $elevTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCounts++;

        }
        
        // return $elevTbl;
        // return view('Company.pdf_files.elevationDrawing', compact('elevTbl'));
        $pdf6 = PDF::loadView('Company.pdf_files.elevationDrawing', ['elevTbl' => $elevTbl]);
        $path6 = public_path() . '/allpdfFile';
        $fileName6 = $id . '6' . '.' . 'pdf';
        // return $pdf6->download('elevation.pdf');
        $pdf6->save($path6 . '/' . $fileName6);

    }
}
