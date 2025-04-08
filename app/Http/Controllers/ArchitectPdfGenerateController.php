<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;
use URL;
use Hash;
use Session;
use View;
use App\Imports\DoorScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PdfMerger;
use App\Models\Item;
use App\Models\User;
use App\Models\Option;
use App\Models\Company;
use App\Models\ConfigurableItems;
use App\Models\ItemMaster;
use App\Models\LippingSpecies;
use App\Models\SettingIntumescentSeals2;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\QuotationShipToInformation;
use App\Models\QuotationVersion;
use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFfooter;
use App\Models\SettingPDFDocument;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\AddIronmongery;
use App\Models\BOMDetails;
use App\Models\Intumescentseals;
use App\Models\GlassType;


class ArchitectPdfGenerateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function generate_quotation(){
    //     $item=Item::get();
    //     // return $item;
    //     return view('Quote.ArchitectQuotation',compact('item'));
    //     $pdf = PDF::loadView('Quote.ArchitectQuotation');
    //     return $pdf->stream('ArchitectQuotation.pdf');
    //     // return view('Quote.ArchitectQuotation');
    // }

     public function generate($quatationId){
        $quatationId = $quatationId;

        $id = Auth::user()->id;
        $comapnyDetail = Company::where('id',$id)->first();
        $quotaion = Quotation::where('id',$quatationId)->first();
        $configurationItem = 1;
        $GlassIntegrity='';
        $FrameFinishForDoorDetailsTable='';
        $frameMaterial='';
        if(!empty($quotaion->configurableitems)){
            $configurationItem = $quotaion->configurableitems;
        }

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id',$quotaion->ProjectId)->first();
        
        $pdf_footer = SettingPDFfooter::where('UserId',$id)->first();

        $SalesContact = 'N/A';
        if(!empty($quotaion->SalesContact)){
            $SalesContact = $quotaion->SalesContact;
        }



        // Elevation Drawing
        $elevTbl = '';
        $ed = Item::where('QuotationId',$quatationId)->get();
// return $ed;
        $TotalItems = count($ed->toArray());

        $PageBreakCount = 1;

        foreach($ed as $tt){

            $countDoorNumber = ItemMaster::where('itemID',$tt->itemId)->count();
            $DoorNumber = ItemMaster::where('itemID',$tt->itemId)->get();

            $doorNo = '';
            foreach($DoorNumber as $bb){

                $doorNo .= '<span style="padding-left:5px;">'.$bb->doorNumber.'</span>';
            }

            $species = LippingSpecies::where('id',$tt->FrameMaterial)->first();

            if($species != ''){
                $frameMaterial = $species->SpeciesName;
                $GlazingBeadSpecies = $species->SpeciesName;

            } else {
                $frameMaterial = empty($tt->FrameMaterial) ? 'N/A' : $tt->FrameMaterial;

                $GlazingBeadSpecies = 'N/A';
            }

            // Overpanel/Fanlight Section :- OP Glazing Bead Species
            $OPspecies = LippingSpecies::where('id',$tt->OPGlazingBeadSpecies)->first();

            $OPGlazingBeadSpecies = $OPspecies != '' ? $OPspecies->SpeciesName : 'N/A';


            $DoorFrameImage = "";
            $VisionPanelGlazingImage = "";



            switch($tt->FireRating){
                case "FD30":
                    switch($tt->DoorsetType){
                        case "SD":
                            // $DoorFrameImage = "fd30/FD30 Single Doorset with VP.png";
                            $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');
                            break;
                        case "DD":
                            // $DoorFrameImage = "fd30/FD30 Double Doorset with VP.png";
                            $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');
                            break;
                    }
                    
                    break;
                case "FD60":
                    switch($tt->DoorsetType){
                        case "SD":
                            // $DoorFrameImage = "fd60/FD60 Single Doorset with VP.png";
                            $DoorFrameImage = Base64Image('FD60SingleDoorsetwithVP');
                            break;
                        case "DD":
                            // $DoorFrameImage = "fd60/FD60 Double Doorset with VP.png";
                            $DoorFrameImage = Base64Image('FD60DoubleDoorsetwithVP');
                            break;
                    }
                    
                    break;
            }

            if($tt->Leaf1VisionPanel == "Yes"){

                $GlazingSystems = $tt->GlazingSystems;

                switch($tt->FireRating){
                    case "FD30":

                        switch($GlazingSystems){
                            case in_array($GlazingSystems,["fireglaze30","Therm-A-Strip_30"]):
                                // $VisionPanelGlazingImage = "fd30/Fireglaze 30 & Therm-A-Strip 30.png";
                                $VisionPanelGlazingImage = Base64Image('Fireglaze30ThermAStrip30');
                                break;
                            case in_array($GlazingSystems,["Firestrip_30","Pyroglaze_30","Therm-A-Bead","ST105GT"]):
                                // $VisionPanelGlazingImage = "fd30/firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT.png";
                                $VisionPanelGlazingImage = Base64Image('firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');
                                break;
                            case in_array($GlazingSystems,["Norsound_Vision_30","Norsound_Universal_30"]):
                                // $VisionPanelGlazingImage = "fd30/Norsound Vision 30 & Universal 30.png";
                                $VisionPanelGlazingImage = Base64Image('NorsoundVision30Universal30');
                                break;
                            case in_array($GlazingSystems,["System_36_Plus","R8193"]):
                                // $VisionPanelGlazingImage = "fd30/System 36 plus & R8193.png";
                                $VisionPanelGlazingImage = Base64Image('System36plusR8193');
                                break;
                            case in_array($GlazingSystems,["Flexible_Figure_1","30049","30054"]):
                                // $VisionPanelGlazingImage = "fd30/System FF1 & 30049 & 30054.png";
                                $VisionPanelGlazingImage = Base64Image('SystemFF13004930054');
                                break;
                        }

                        break;
                    case "FD60":

                        switch($GlazingSystems){
                            case $GlazingSystems == "Firestrip_60":
                                // $VisionPanelGlazingImage = "fd60/Firestrip 60.png";
                                $VisionPanelGlazingImage = Base64Image('Firestrip60');
                                break;
                            case in_array($GlazingSystems,["Norsound_Vision_60","Norsound_Universal_60"]):
                                // $VisionPanelGlazingImage = "fd60/Norsound Vision 60 & Norsound Universal 60.png";
                                $VisionPanelGlazingImage = Base64Image('NorsoundVision60NorsoundUniversal60');
                                break;
                            case $GlazingSystems == "Pyroglaze_60":
                                // $VisionPanelGlazingImage = "fd60/Pyroglaze 60.png";
                                $VisionPanelGlazingImage = Base64Image('Pyroglaze60');
                                break;
                            case $GlazingSystems == "ST105GT":
                                // $VisionPanelGlazingImage = "fd60/ST105GT.png";
                                $VisionPanelGlazingImage = Base64Image('ST105GT');
                                break;
                            case $GlazingSystems == "System_36_Plus":
                                // $VisionPanelGlazingImage = "fd60/System 36 plus.png";
                                $VisionPanelGlazingImage = Base64Image('System36plus');
                                break;
                            case $GlazingSystems == "System_63":
                                // $VisionPanelGlazingImage = "fd60/System 63.png";
                                $VisionPanelGlazingImage = Base64Image('System63');
                                break;
                            case in_array($GlazingSystems,["Therm-A-Glaze_60","FG60","Fireglaze_60"]):
                                // $VisionPanelGlazingImage = "fd60/Therm-A-Glaze 60 & Fireglaze Mastic & FG60.png";
                                $VisionPanelGlazingImage = Base64Image('ThermAGlaze60FireglazeMasticFG60');
                                break;
                        }

                        break;
                }
            }


            $QuotationGenerationId = null;

            if(!empty($quotaion->QuotationGenerationId)){

                $QuotationGenerationId = $quotaion->QuotationGenerationId;

            }
            
            $ProjectName = null;
            if(!empty($project->ProjectName)){
                $ProjectName = $project->ProjectName;
            }
            
            if(!empty($version)) {
                $version = $version;
            }
            
            $CompanyAddressLine1 = null;
            if(!empty($comapnyDetail->CompanyAddressLine1)) {
                $CompanyAddressLine1 = $comapnyDetail->CompanyAddressLine1;
            }
            
            $Username = null;
            if(!empty($user->FirstName) && !empty($user->LastName)){
                $Username = $user->FirstName.' '.$user->LastName;
            }

            if (!empty($tt->SvgImage)) {
                $svgFile = strpos($tt->SvgImage,'.png') !== false ? URL('/').'/uploads/files/'.$tt->SvgImage : $tt->SvgImage;
            } else {
                $svgFile = URL('/').'/uploads/files/door.jpg';
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


                                                <td colspan="3"><span>'.$QuotationGenerationId.'</span></td>
                                                <td class="tbl_color"><span>Project</span></td>
                                                <td><span>'.$ProjectName.'</span></td>
                                                <td class="tbl_color"><span>Prepared By</span></td>
                                                <td><span>'.$Username.'</span></td>
                                            </tr>
                                            <tr>
                                                <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>

                                                <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                                <td><span >'.date('Y-m-d').'</span></td>
                                                <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
                            // $elevTbl .= '<tr>
                            //                     <td colspan="2">
                            //                         <div class="doorImgBox">
                            //                             <img src="'.URL('/').'/uploads/files/'.$svgFile.'" class="doorImg">
                            //                         </div>
                            //                     </td>
                            //                 </tr>';
            $elevTbl .= '<tr>';

            if($tt->Leaf1VisionPanel == "Yes") {
                //                        $elevTbl .= '<td style="width:50%;">
                //                                    <p class="fanlightframe_t1">23mm Pilkington Pyrostop -60-101</p>
                //                                    <p class="fanlightframe_t2">-Norseal Vision 60 -25 x 3mm</p>
                //                                    <p class="fanlightframe_t3">50mm Lg. Steel Pin @ 150mm c/c</p>
                //                                    <p class="fanlightframe_t4">-Sapele -27 x 24.5mm</p>
                //                                    <p class="fanlightframe_t5">- Norseal 60B -2mm x 42mm</p>
                //                                    <p class="fanlightframe_t6">-Rebate:3 x 3mm -Both Frames</p>
                //                                    <p class="fanlightframe_t7">-Pyroplex Rigid Box -15mm x 4mm -Twin Flipper x 1 -Flat x 1 -CF822</p>
                //                                    <p class="fanlightframe_t8">-Strebord -FD60 -54mm -Chilt A032067 -Rev.M</p>
                //                                    <p class="fanlightframe_t9">-Sapele</p>
                //                                    <div class="doorImgBox">
                //                                        <img src="'.URL('/').'/uploads/files/fanlightframe.jpg" class="doorImg" style="position:relative;">
                //                                    </div></td>';

                $GlassType = "";

                $SelectedGlassType = Option::where("configurableitems",$configurationItem)
                                ->where("firerating",$tt->FireRating)
                                ->where("OptionKey",$tt->GlassType)->first();
                                // return $SelectedGlassType;
                if($SelectedGlassType != null){
                    $GlassType = $SelectedGlassType->OptionValue;
                }

                //                $SelectedFixingDetails = Option::where("configurableitems",$configurationItem)
                //                    ->where("firerating",$tt->FireRating)
                //                    ->where("UnderAttribute",$tt->GlazingSystems)
                //                    ->where("OptionSlug","Fixing_Detail")->first();
                //                if($SelectedFixingDetails != null){
                //                    $FixingDetails = $SelectedFixingDetails->OptionValue;
                //                }

                $GlazingBeadSpecies = "";

                $SelectedGlazingBeadSpecies = LippingSpecies::find($tt->GlazingBeadSpecies);

                if($SelectedGlazingBeadSpecies != null){
                    $GlazingBeadSpecies = $SelectedGlazingBeadSpecies->SpeciesName;
                }

                $ConfigurableItems = "Streboard";

                $SelectedConfigurableItems = ConfigurableItems::find($configurationItem);

                if($SelectedConfigurableItems != null){
                    $ConfigurableItems = $SelectedConfigurableItems->name;
                }

                $elevTbl .= '<td style="width:30%;">
                                    <p class="visionpanel_t1">'.$tt->GlassThickness.'mm '.$GlassType.'</p>
                                    <p class="visionpanel_t2">'.$tt->glazingBeadsFixingDetail.'</p>
                                    <p class="visionpanel_t3">-'.$GlazingBeadSpecies.'<br>-'.$tt->glazingBeadsWidth.' x '.$tt->glazingBeadsHeight.'mm</p>
                                    <p class="visionpanel_t4">-'.$ConfigurableItems.'<br>-'.$tt->LeafThickness.'mm</p>
                                    <p class="visionpanel_t5">'.$tt->LeafThickness.'</p>
                                    <div class="doorImgBox">'. $VisionPanelGlazingImage .'</div>
                                </td>';
                // $elevTbl .= '<td style="width:50%;"></td>';
            }


           $IsLeafEnabled = $tt->Leaf1VisionPanel == "Yes" ? 'style="width:70%;"' : 'colspan="2"';

           $elevTbl .= '<td '. $IsLeafEnabled .'>
               <div class="doorImgBox">

                <img src="'.$svgFile.'" class="doorImg">
               </div>
           </td>
        </tr>';
        // return $elevTbl;
            if ($tt->Leaf1VisionPanel == "Yes" && isset($DoorFrameImage)) {
                $FrameMaterial = 'N/A';
                if (!empty($tt->FrameMaterial) && !in_array($tt->FrameMaterial,["MDF","Softwood","Hardwood"])) {
                    $SelectedFrameMaterial = LippingSpecies::find($tt->FrameMaterial);
                    if($SelectedFrameMaterial != null){
                        $FrameMaterial = $SelectedFrameMaterial->SpeciesName;

                        $FrameMaterial .= "<br>-".(($SelectedFrameMaterial->MinValue > 0)?$SelectedFrameMaterial->MinValue."x":"").$SelectedFrameMaterial->MaxValues." Kg/m3";

                    }else{
                        $SelectedFrameMaterial = LippingSpecies::where("SpeciesName",$tt->FrameMaterial)->first();
                        if($SelectedFrameMaterial != null){
                            $FrameMaterial = $SelectedFrameMaterial->SpeciesName;
                            $FrameMaterial .= "<br>-".(($SelectedFrameMaterial->MinValue > 0)?$SelectedFrameMaterial->MinValue."x":"").$SelectedFrameMaterial->MaxValues." Kg/m3";
                        }
                    }
                }

                $maxIN = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);
                switch($tt->DoorsetType){

                    case "SD":
                        $elevTbl .= '<tr>
                                    <td colspan="2" style="">
                                        <p class="frame_sd_t1">-'.$FrameMaterial.'</p>
                                        <p class="frame_sd_t2">-'.$ConfigurableItems.'<br>-'.$tt->LeafThickness.'mm</p>
                                        <p class="frame_sd_t3">-'.$GlazingBeadSpecies.'<br>-'.$tt->glazingBeadsWidth.' x '.$tt->glazingBeadsHeight.'mm</p>
                                        <p class="frame_sd_t4">-'.$tt->GlassThickness.'mm '.$GlassType.'</p>
                                        <p class="frame_sd_z1">'.$tt->LeafWidth1.'</p>
                                        <p class="frame_sd_z2">'.$tt->FrameWidth.'</p>
                                        <p class="frame_sd_z3">'.$tt->Leaf1VPWidth.'</p>
                                        <p class="frame_sd_z4">'.$maxIN.'</p>
                                        <p class="frame_sd_z5">'.$tt->DistanceFromTheEdgeOfDoor.'</p>
                                        '.$DoorFrameImage.'
                                    </td>
                                </tr>';

                        break;

                    case "DD":
                        $elevTbl .= '<tr>
                                    <td colspan="2" style="">
                                        <p class="frame_dd_t1">-'.$FrameMaterial.'</p>
                                        <p class="frame_dd_t2">-'.$ConfigurableItems.'<br>-'.$tt->LeafThickness.'mm</p>
                                        <p class="frame_dd_t3">-'.$GlazingBeadSpecies.'<br>-'.$tt->glazingBeadsWidth.' x '.$tt->glazingBeadsHeight.'mm</p>
                                        <p class="frame_dd_t4">-'.$tt->GlassThickness.'mm '.$GlassType.'</p>
                                        <p class="frame_dd_t5">-'.$tt->GAP.'mm GAP</p>
                                        <p class="frame_sd_DD1">'.$tt->FrameWidth.'</p>
                                        <p class="frame_sd_DD2">'.$tt->DistanceFromTheEdgeOfDoor.'</p>
                                        <p class="frame_sd_DD3">'.$tt->DistanceFromTheEdgeOfDoorforLeaf2.'</p>
                                        <p class="frame_sd_DD4">'.$tt->Leaf1VPWidth.'</p>
                                        <p class="frame_sd_DD5">'.$tt->LeafWidth1.'</p>
                                        <p class="frame_sd_DD6">'.$tt->GAP.'</p>
                                        '.$DoorFrameImage.'
                                    </td>
                                </tr>';
                        break;

                }
            }


            $ExtLinerValue = '';
            if(!empty($tt->ExtLinerValue)){
                $ExtLinerValue = $tt->ExtLinerValue;
            }
            
            $ExtLinerThickness = '';
            if(!empty($tt->ExtLinerThickness)){
                $ExtLinerThickness = $tt->ExtLinerThickness."mm";
            }

            if (empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                $ExtLinerSizeForDoorDetailsTable = "N/A";
            } elseif (empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                $ExtLinerSizeForDoorDetailsTable = 'N/A x '.$ExtLinerThickness;
            } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue.' x N/A';
            } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue.' x '.$ExtLinerThickness;
            }

            $SpeciesName = 'N/A';
            if(!empty($tt->LippingSpecies)){
                $ls = LippingSpecies::where('id',$tt->LippingSpecies)->first();
                $SpeciesName = $ls->SpeciesName;
            }

            // $intumescentSealType = 'N/A';
            // $IntumescentLeapingSealArrangement = $tt->IntumescentLeapingSealArrangement;
            // if(!empty($IntumescentLeapingSealArrangement)){
            //     $intum = SettingIntumescentSeals2::select('intumescentSeals')->where('id',$IntumescentLeapingSealArrangement)->first();
            //     $intumescentSealType = $intum->intumescentSeals;
            // }

            $intumescentSealType = 'N/A';
            if(!empty($tt->IntumescentLeapingSealType)){
                $intumescentSealType = IntumescentSealType($configurationItem , $tt->IntumescentLeapingSealType);
            }

            $DoorLeafFinish = "N/A";
            if(!empty($tt->DoorLeafFinish)){
                $dlf = DoorLeafFinish($configurationItem,$tt->DoorLeafFinish);
                $DoorLeafFinish = empty($tt->SheenLevel) ? $dlf : $dlf.' - '.$tt->SheenLevel.' Sheen';
            }

            $DoorLeafFinishColor = '';
            if(!empty($tt->DoorLeafFinishColor)){
                $DoorLeafFinishColor = ' + '.$tt->DoorLeafFinishColor;
            }

            $DoorLeafFacing = "N/A";
            if(!empty($tt->DoorLeafFacing)){
                $DoorLeafFacing = DoorLeafFacing($configurationItem,$tt->DoorLeafFacing,$tt->DoorLeafFacingValue);
            }

            $FrameTypeForDoorDetailsTable = 'N/A';
            if(!empty($tt->FrameType)){
                $FrameTypeForDoorDetailsTable = FrameType($configurationItem,$tt->FrameType);
            }

            $FrameFinishForDoorDetailsTable = 'N/A';
            if(!empty($tt->FrameFinish)){
                $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem,$tt->FrameFinish,$tt->FrameFinishColor);
            }

            $LippingType = "N/A";
            if(!empty($tt->LippingType)){
                $SelectedLippingType = Option::where("configurableitems",$configurationItem)
                ->where("OptionSlug","lipping_type")
                ->where("OptionKey",$tt->LippingType)->first();
                if($SelectedLippingType != null){
                    $LippingType = $SelectedLippingType->OptionValue;
                }
            }


            $GlassTypeForDoorDetailsTable = "N/A";
            if(!empty($tt->GlassType)){
                $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem,$tt->FireRating,$tt->GlassType,$tt->GlassThickness);
            }
            
            $OPGlassTypeForDoorDetailsTable = "N/A";
            if(!empty($tt->OPGlassType)){
                $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem,$tt->FireRating,$tt->OPGlassType);
            }
            
            $ArchitraveFinishForDoorDetailsTable = "N/A";
            if(!empty($tt->ArchitraveFinish)){
                $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem,$tt->ArchitraveFinish,$tt->FrameFinishColor);
            }

            $GlassIntegrity = 'N/A';

            if(!empty($tt->GlassIntegrity)){
                $gi = Option::where("configurableitems",$configurationItem)
                ->where("firerating",$tt->FireRating)
                ->where("OptionSlug","Glass_Integrity")
                ->where("OptionKey",$tt->GlassIntegrity)->first();
                $GlassIntegrity = $gi->OptionValue;
            }
            
            $OPGlazingBeads = 'N/A';
            if(!empty($tt->OPGlazingBeads)){
                $opgb = Option::where("configurableitems",$configurationItem)
                ->where("firerating",$tt->FireRating)
                ->where("OptionSlug","leaf1_glazing_beads")
                ->where("OptionKey",$tt->OPGlazingBeads)->first();
                $OPGlazingBeads = $opgb->OptionValue;
            }
            
            $SLBeadingType = 'N/A';
            if(!empty($tt->BeadingType)){
                $bt = Option::where("configurableitems",$configurationItem)
                ->where("firerating",$tt->FireRating)
                ->where("OptionSlug","leaf1_glazing_beads")
                ->where("OptionKey",$tt->BeadingType)->first();
                $SLBeadingType = $bt->OptionValue;
            }

            $glazingSystems = 'N/A';
            if(!empty($tt->GlazingSystems)){
                $gs = Option::where('configurableitems',$configurationItem)->where('UnderAttribute',$tt->FireRating)->where('OptionKey',$tt->GlazingSystems)
                ->where('OptionSlug','leaf1_glazing_systems')->first();
                $glazingSystems = $gs->OptionValue;
            }
            
            if($tt->SwingType==''){
            if ($tt->SwingType == 'SA') {
                $SwingType = 'Single Acting';
            } elseif ($tt->SwingType == 'DA') {
                $SwingType = 'Double Acting';
            }
        }
        else{
            $SwingType = '';
        }

            // Under the row ‘Decorative Groves’ this should show the width x depth. Example 5mm wide x 2mm deep
            if (!empty($tt->DecorativeGroves)) {
                $GrooveWidth = empty($tt->GrooveWidth) ? 'N/A' : $tt->GrooveWidth .'mm wide';

                $GrooveDepth = empty($tt->GrooveDepth) ? 'N/A' : $tt->GrooveDepth .'mm deep';

                $DecorativeGroves = empty($tt->GrooveWidth) && empty($tt->GrooveDepth) ? 'N/A' : $GrooveWidth .' x '. $GrooveDepth;
            } else {
                $DecorativeGroves = 'N/A';
            }

            $leafWidth1 = empty($tt->LeafWidth1) ? 'N/A' : $tt->LeafWidth1;

            $leafWidth2 = empty($tt->LeafWidth2) ? 'N/A' : $tt->LeafWidth2;

            $LeafHeight = empty($tt->LeafHeight) ? 'N/A' : $tt->LeafHeight;

            $LeafThickness = empty($tt->LeafThickness) ? 'N/A' : $tt->LeafThickness;

            $FrameDepth = empty($tt->FrameDepth) ? 'N/A' : $tt->FrameDepth;

            if(!empty($tt->IronmongerySet)){
                if ($tt->IronmongerySet == 'No') {
                    $IronmongerySet = 'N/A';
                } elseif (!empty($tt->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($tt->IronmongeryID);
                } else {
                    $IronmongerySet = 'N/A';
                }
            } else {
                $IronmongerySet = 'N/A';
            }
            
            $rWdBRating = 'N/A';
            if(!empty($tt->rWdBRating)){
                $rWdBRating = $tt->rWdBRating;
            }



            $intumescentSealArrangement = 'N/A';
            if(!empty($tt->IntumescentLeapingSealArrangement)){
                $Intumescentseals = Intumescentseals::where('id',$tt->IntumescentLeapingSealArrangement)->first();
                $intumescentSealArrangement =  $Intumescentseals->brand;
            }

            $IntumescentLeapingSealColor = 'N/A';
            if(!empty($tt->IntumescentLeapingSealColor)){
                $IntumescentLeapingSealColor = $tt->IntumescentLeapingSealColor;
            }

            $ArchitraveMaterial = 'N/A';
            if(!empty($tt->ArchitraveMaterial)){
                if($tt->ArchitraveMaterial == 'Softwood' || $tt->ArchitraveMaterial == 'MDF' || $tt->ArchitraveMaterial == 'Hardwood'){
                    $ArchitraveMaterial = 'N/A';
                } else {
                    $ls = LippingSpecies::where('id',$tt->ArchitraveMaterial)->first();
                    $ArchitraveMaterial = $ls->SpeciesName;
                }
            }
            
            $ArchitraveSetQty = 'N/A';
            if(!empty($tt->ArchitraveSetQty)){
                $ArchitraveSetQty = $tt->ArchitraveSetQty;
            }
            
            $ArchitraveWidth = 'N/A';
            if(!empty($tt->ArchitraveWidth)){
                $ArchitraveWidth = $tt->ArchitraveWidth;
            }
            
            $ArchitraveDepth = 'N/A';
            if(!empty($tt->ArchitraveDepth)){
                $ArchitraveDepth = $tt->ArchitraveDepth;
            }
            
            $ArchitraveHeight = 'N/A';
            if(!empty($tt->ArchitraveHeight)){
                $ArchitraveHeight = $tt->ArchitraveHeight;
            }



            // Add a new section called 'Side Screen Section' SL1 Glass Type , Beading Type and Glazing Bead Species.
            $configurationDoor = configurationDoor($configurationItem);
            $fireRatingDoor = fireRatingDoor($tt->FireRating);
            $sl1glasstype = 'N/A';
            if(!empty($tt->SideLight1GlassType)){
                $op = GlassType::where($configurationDoor,$configurationItem)
                    ->where($fireRatingDoor,$tt->FireRating)
                    ->where("Key",$tt->SideLight1GlassType)->first();
                $sl1glasstype = $op->GlassType;
            }

            $beadingtype = 'N/A';
            if(!empty($tt->SideLight2BeadingType)){
                $op2 = Option::where(['configurableitems' => $configurationItem , 'UnderAttribute' => $tt->FireRating , 'OptionSlug' => 'leaf1_glazing_beads' , 'OptionKey' => $tt->SideLight2BeadingType ])->first();
                $beadingtype = $op2->OptionValue;
            }

            $glazingbeadspecies = 'N/A';
            if(!empty($tt->SL1GlazingBeadSpecies)){
                $ls = LippingSpecies::where('id',$tt->SL1GlazingBeadSpecies)->first();
                if(!empty($ls->SpeciesName)){
                    $glazingbeadspecies = $ls->SpeciesName;
                }
            }

            $VPBeadingType = 'N/A';
            if(!empty($tt->GlazingBeads)){
                $VPBeadingType = VPBeadingType($configurationItem , 'leaf1_glazing_beads' , $tt->GlazingBeads);
            }

            $elevTbl .= '</table>
                    </div>
                    <div id="section-right">
                        <table id="WithBorder" class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                                    <td class="dicription_blank"><b>Type '.$tt->DoorType.'</b></td>
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
                                    <td class="dicription_blank">'.$tt->DoorType.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">'.$tt->FireRating.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Swing Type</td>
                                    <td class="dicription_blank">'.$SwingType .' '. $tt->SwingType.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Open Inwards</td>
                                    <td class="dicription_blank">'.$tt->OpensInwards.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Handing</td>
                                    <td class="dicription_blank">'.$tt->Handing.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Undercut</td>
                                    <td class="dicription_blank">'.$tt->Undercut.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Thickness</td>
                                    <td class="dicription_blank">'.$tt->FrameThickness.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Width</td>
                                    <td class="dicription_blank">'.$tt->SOWidth.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Height</td>
                                    <td class="dicription_blank">'.$tt->SOHeight.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Depth</td>
                                    <td class="dicription_blank">'.$tt->SOWallThick.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door leaf Facing</td>
                                    <td class="dicription_blank">'.$DoorLeafFacing.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">'.$DoorLeafFinish . $DoorLeafFinishColor.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">'.$DecorativeGroves.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Width 1</td>
                                    <td class="dicription_blank">'.$leafWidth1.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Width 2</td>
                                    <td class="dicription_blank">'.$leafWidth2.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Height</td>
                                    <td class="dicription_blank">'.$LeafHeight.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Leaf Thickness</td>
                                    <td class="dicription_blank">'.$LeafThickness.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Frame</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Material</td>
                                    <td class="dicription_blank">'.$frameMaterial.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Type</td>
                                    <td class="dicription_blank">'.$FrameTypeForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Width</td>
                                    <td class="dicription_blank">'.$tt->FrameWidth.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Height</td>
                                    <td class="dicription_blank">'.$tt->FrameHeight.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Depth</td>
                                    <td class="dicription_blank">'.$FrameDepth.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Finish</td>
                                    <td class="dicription_blank">'.$FrameFinishForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Ext-Liner</td>
                                    <td class="dicription_blank">'.$tt->ExtLiner.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Ext-Liner Size</td>
                                    <td class="dicription_blank">'.$ExtLinerSizeForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Ironmongery Set</td>
                                    <td class="dicription_blank">'.$IronmongerySet.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Lipping And Intumescent</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Type</td>
                                    <td class="dicription_blank">'.$intumescentSealType.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Lipping Species</td>
                                    <td class="dicription_blank">'.$SpeciesName.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Intumescent Seal Brand</td>
                                    <td class="dicription_blank">'.$intumescentSealArrangement.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Intumescent Seal Color</td>
                                    <td class="dicription_blank">'.$IntumescentLeapingSealColor.'</td>
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
                                    <td class="dicription_blank">'.$GlassIntegrity.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Type + Thickness</td>
                                    <td class="dicription_blank">'.$GlassTypeForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">'.$GlazingBeadSpecies.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Beading Type</td>
                                    <td class="dicription_blank">'.$VPBeadingType.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing System</td>
                                    <td class="dicription_blank">'.$glazingSystems.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Overpanel/Fanlight Section</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glass Type</td>
                                    <td class="dicription_blank">'.$OPGlassTypeForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glazing Beads</td>
                                    <td class="dicription_blank">'.$OPGlazingBeads.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Glazing Bead Species</td>
                                    <td class="dicription_blank">'.$OPGlazingBeadSpecies.'</td>
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
                                    <td class="dicription_blank">'.$sl1glasstype.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Beading Type</td>
                                    <td class="dicription_blank">'.$SLBeadingType.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">'.$glazingbeadspecies.'</td>
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
                                    <td class="dicription_blank">'.$ArchitraveMaterial.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Finish</td>
                                    <td class="dicription_blank">'.$ArchitraveFinishForDoorDetailsTable.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Set Qty</td>
                                    <td class="dicription_blank">'.$ArchitraveSetQty.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Width</td>
                                    <td class="dicription_blank">'.$ArchitraveWidth.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Depth</td>
                                    <td class="dicription_blank">'.$ArchitraveDepth.'</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Height</td>
                                    <td class="dicription_blank">'.$ArchitraveHeight.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Accoustics</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Rating</td>
                                    <td class="dicription_blank">'.$rWdBRating.'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div id="footer">
                        <h3><b>Total Doorsets: '.$countDoorNumber.'</b></h3>
                        <div class="tb14">'.$doorNo.'</div>
                    </div>
                ';

            if($PageBreakCount < $TotalItems){
                $elevTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;
        }

        $FinalElavationDetails = [];

        if($ed !== null){

            $EDData = $ed->toArray();

            $ChunkedEDData = array_chunk($EDData, 5);

            foreach($ChunkedEDData as $key => $val){

                $ElavationDetails = [
                    "DoorName" => [],
                    "Elevation" => [],
                    "Plan" => [],
                    "Type" => [],
                    "StructuralOpening" => [],
                    "DoorSize" => [],
                    "FireRating" => [],
                    "Frame" => [],
                    "VisionPanel" => [],
                    "FinishColour" => [],
                    "Quantity" => [],
                    "Notes" => [],
                    "NBSRef" => []
                ];

                foreach($val as $EDkey => $EDval){
                    $ElavationDetails["DoorName"][] = $EDval['doorNumber'] ?? '';
                    $ElavationDetails["Elevation"][] = $EDval['SvgImage'] ?? '';
                    $ElavationDetails["Plan"][] = "";
                    $ElavationDetails["Type"][] = $EDval['DoorType'] ?? '';
                    $ElavationDetails["StructuralOpening"][] = $EDval['SOWidth'].'mm (w) X '.$EDval['SOHeight'].'(h)';
                    $ElavationDetails["DoorSize"][] = $EDval['SOWidth'].'mm (w) X '.$EDval['SOHeight'].'(h)';
                    $ElavationDetails["FireRating"][] = $EDval['FireRating'] ?? '';
                    $ElavationDetails["Frame"][] = $EDval['FrameMaterial'] ?? '';
                    $ElavationDetails["VisionPanel"][] = $EDval['GlassIntegrity'] ?? '';
                    $ElavationDetails["FinishColour"][] = $EDval['DoorLeafFinish'] ?? '';
                    $ElavationDetails["Quantity"][] = "";
                    $ElavationDetails["Notes"][] = "";
                    $ElavationDetails["NBSRef"][] = "";
                }

                $FinalElavationDetails[] = $ElavationDetails;
            }
        }


        // $pdf = PDF::loadView('Quote.test' ,compact('TotalItems','FinalElavationDetails','elevTbl','ed','GlassIntegrity','FrameFinishForDoorDetailsTable','frameMaterial'));
          //    return $pdf->stream('ArchitectQuotation.pdf');
        // $path = public_path().'/pdf';
        //    $fileName = $id.'1' . '.' . 'pdf' ;
        //    $pdf->save($path . '/' . $fileName);


           $pdf2 = PDF::loadView('Quote.NewQuatation',['ed' => $ed]);
                return $pdf2->stream('ArchitectQuotation.pdf');
           $path2 = public_path().'/pdf';
           $fileName2 = $id.'2' . '.' . 'pdf' ;
           $pdf2->save($path2 . '/' . $fileName2);

           $PDFfilename = public_path().'/pdf'.'/'.$quotaion->QuotationGenerationId.'.pdf';

            // Merge the PDF File
        $pdf = public_path().'/pdf'.'/'.$fileName;
        $pdf2 = public_path().'/pdf'.'/'.$fileName2;
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($pdf, 'all');
        $pdfMerger->addPDF($pdf2, 'all');

        $pdfMerger->merge();
        $pdfMerger->save($PDFfilename);
        $pdfMerger->save("test.pdf",'download');

        // $file = public_path().'/pdf'.'/'.$quotaion->QuotationGenerationId.'.pdf';
        // if(file_exists($file)){
        //     unlink($file);
        // }
        // $quo = Quotation::find($quatationId);
        // $quo->quotTag = 1;
        // $quo->save();
        // $pdfMerger->save($file);

        $unlinkpath1 = public_path().'/pdf'.'/'.$fileName;
        $unlinkpath2 = public_path().'/pdf'.'/'.$fileName2;

        unlink($unlinkpath1);
        unlink($unlinkpath2);





        // return $pdf2->stream('ArchitectQuotation.pdf');

        // $pdf3 = PDF::loadView('Quote.ArchitectQuotation',compact('elevTbl'));
        // return $pdf3->stream('ArchitectQuotation.pdf');

     }



}
