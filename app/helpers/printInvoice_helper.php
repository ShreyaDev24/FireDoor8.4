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
use App\Models\IronmongeryInfoModel;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedOption;
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

function GlazingSystemsImage($GlazingSystems,$GlazingBeads,$FireRating): array{
    $VisionPanelGlazingImageStructure = '';
    $VisionPanelGlazingImageRight = '';
    $VisionPanelGlazingImageLeft  = '';
    $VisionPanelGlazingImageStructure_Frame = '';
    $GlazingBeadsPadding = 0;
    // dd($GlazingBeads,$FireRating,'122',$GlazingBeadsPadding,$VisionPanelGlazingImageStructure_Frame,$VisionPanelGlazingImageRight);
    switch ($FireRating) {
        case in_array($FireRating, ["FD30", "FD30s"]):

            // switch ($GlazingSystems) {
            //     case in_array($GlazingSystems, ["fireglaze30", "Therm-A-Strip_30"]):
            //         //    $VisionPanelGlazingImage = Base64Image('Fireglaze30ThermAStrip30');

            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Fireglaze30ThermAStrip30');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Fireglaze30ThermAStrip30_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Firestrip_30", "Pyroglaze_30", "Therm-A-Bead", "ST105GT"]):
            //         //$VisionPanelGlazingImage = Base64Image('firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');

            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT_Frame');

            //         break;
            //     case in_array($GlazingSystems, ["Norsound_Vision_30", "Norsound_Universal_30"]):
            //         // $VisionPanelGlazingImage = Base64Image('NorsoundVision30Universal30');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.NorsoundVision30Universal30');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.NorsoundVision30Universal30_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_36_Plus", "R8193"]):
            //         // $VisionPanelGlazingImage = Base64Image('System36plusR8193');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System36plusR8193');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System36plusR8193_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Flexible_Figure_1", "30049", "30054"]):
            //         // $VisionPanelGlazingImage = Base64Image('SystemFF13004930054');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.SystemFF13004930054');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.SystemFF13004930054_Frame');
            //         break;
            //     default:
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');;
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT_Frame');
            //         break;
            // }

            switch ($GlazingBeads) {

                case in_array($GlazingBeads, ["Splayed_Bolection","Chamfer_Boleaction"]):
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedBolectionFD30');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedBolectionFD30Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedBolectionFD30sectiondrawing');
                    $GlazingBeadsPadding = 1;
                    break;
                    case in_array($GlazingBeads, ["Splayed_Flush","Square_Boleaction"]):

                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SquareBolectionFD30');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareBolectionFD30sectiondrawing');
                    $GlazingBeadsPadding = 1;
                    break;

                case $GlazingBeads == "Square_Flush":

                        $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SquareFlushFD30');
                        // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD60Left');
                        $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareFlushFD30sectiondrawing');
                        $GlazingBeadsPadding = 0;
                        break;

                default:

                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedFlushFD30');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedFlushFD30sectiondrawing');
                    $GlazingBeadsPadding = 0;
                    break;
            }

            break;
        case in_array($FireRating, ["FD60", "FD60s"]):

            // switch ($GlazingSystems) {
            //     case in_array($GlazingSystems, ["Firestrip_60"]):
            //         //
            //         // $VisionPanelGlazingImage = Base64Image('Firestrip60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Firestrip60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Firestrip60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Norsound_Vision_60", "Norsound_Universal_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('NorsoundVision60NorsoundUniversal60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.NorsoundVision60NorsoundUniversal60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.NorsoundVision60NorsoundUniversal60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Pyroglaze_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('Pyroglaze60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Pyroglaze60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Pyroglaze60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["ST105GT"]):
            //         // $VisionPanelGlazingImage = Base64Image('ST105GT');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ST105GT');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.ST105GT_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_36_Plus"]):
            //         // $VisionPanelGlazingImage = Base64Image('System36plus');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System36plus');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System36plus_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_63"]):
            //         // $VisionPanelGlazingImage = Base64Image('System63');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System63');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System63_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Therm-A-Glaze_60", "FG60", "Fireglaze_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('ThermAGlaze60FireglazeMasticFG60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ThermAGlaze60FireglazeMasticFG60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.ThermAGlaze60FireglazeMasticFG60_Frame');
            //         break;

            //     default:
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ST105GT');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.ST105GT_Frame');
            //         break;
            // }

            // $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ST105GT');
            // $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Pyroglaze60_Frame');

            switch ($GlazingBeads) {
                case in_array($GlazingBeads, ["Splayed_Bolection","Chamfer_Boleaction"]):
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedBolectionFD60');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedBolectionFD60Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedBolectionFD60sectiondrawing');
                    $GlazingBeadsPadding = 1;
                    break;
                case $GlazingBeads == "Chamfered_Boleaction":
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedBolectionFD60');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedBolectionFD60Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedBolectionFD60sectiondrawing');
                    $GlazingBeadsPadding = 1;
                    break;
                case in_array($GlazingBeads, ["Flush_Square","Square_Flush"]):

                    $VisionPanelGlazingImageRight = \Config::get('constants.SquareFlushFD60.SquareFlushFD60');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD60Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareFlushFD60sectiondrawing');
                    $GlazingBeadsPadding = 0;
                    break;

                case $GlazingBeads == "Square_Boleaction":

                        $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SquareBolectionFD60');
                        // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
                        $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareBolectionFD60sectiondrawing');
                        $GlazingBeadsPadding = 1;
                        break;

                default:

                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedFlushFD60');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD60Left');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedFlushFD60sectiondrawing');
                    $GlazingBeadsPadding = 0;
                    break;
            }

            break;

        case $FireRating == "NFR":

            // switch ($GlazingSystems) {
            //     case in_array($GlazingSystems, ["fireglaze30", "Therm-A-Strip_30"]):
            //         //    $VisionPanelGlazingImage = Base64Image('Fireglaze30ThermAStrip30');

            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Fireglaze30ThermAStrip30');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Fireglaze30ThermAStrip30_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Firestrip_30", "Pyroglaze_30", "Therm-A-Bead", "ST105GT"]):
            //         //$VisionPanelGlazingImage = Base64Image('firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');

            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT_Frame');

            //         break;
            //     case in_array($GlazingSystems, ["Norsound_Vision_30", "Norsound_Universal_30"]):
            //         // $VisionPanelGlazingImage = Base64Image('NorsoundVision30Universal30');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.NorsoundVision30Universal30');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.NorsoundVision30Universal30_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_36_Plus", "R8193"]):
            //         // $VisionPanelGlazingImage = Base64Image('System36plusR8193');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System36plusR8193');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System36plusR8193_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Flexible_Figure_1", "30049", "30054"]):
            //         // $VisionPanelGlazingImage = Base64Image('SystemFF13004930054');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.SystemFF13004930054');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.SystemFF13004930054_Frame');
            //         break;

            //     case in_array($GlazingSystems, ["Firestrip_60"]):
            //         //
            //         // $VisionPanelGlazingImage = Base64Image('Firestrip60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Firestrip60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Firestrip60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Norsound_Vision_60", "Norsound_Universal_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('NorsoundVision60NorsoundUniversal60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.NorsoundVision60NorsoundUniversal60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.NorsoundVision60NorsoundUniversal60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Pyroglaze_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('Pyroglaze60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.Pyroglaze60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.Pyroglaze60_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["ST105GT"]):
            //         // $VisionPanelGlazingImage = Base64Image('ST105GT');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ST105GT');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.ST105GT_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_36_Plus"]):
            //         // $VisionPanelGlazingImage = Base64Image('System36plus');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System36plus');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System36plus_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["System_63"]):
            //         // $VisionPanelGlazingImage = Base64Image('System63');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.System63');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.System63_Frame');
            //         break;
            //     case in_array($GlazingSystems, ["Therm-A-Glaze_60", "FG60", "Fireglaze_60"]):
            //         // $VisionPanelGlazingImage = Base64Image('ThermAGlaze60FireglazeMasticFG60');
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.ThermAGlaze60FireglazeMasticFG60');
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.ThermAGlaze60FireglazeMasticFG60_Frame');
            //         break;
            //     default:
            //         $VisionPanelGlazingImageStructure = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT');;
            //         $VisionPanelGlazingImageStructure_Frame = \Config::get('constants.base64Images.firestrip_30_n_pyroglaze_30_n_therm_a_bead_n_ST105GT_Frame');
            //         break;
            // }

            switch ($GlazingBeads) {
                case in_array($GlazingBeads, ["Splayed_Bolection","Chamfer_Boleaction"]):
                    // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedBolectionFD30Right');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedBolectionFD30Left');
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedBolectionFD30');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedBolectionFD30sectiondrawing');
                    $GlazingBeadsPadding = 1;

                    break;
                case in_array($GlazingBeads, ["Splayed_Flush","Square_Boleaction"]):

                    // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SquareBolectionFD30');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareBolectionFD30sectiondrawing');
                    $GlazingBeadsPadding = 1;

                    break;

                case in_array($GlazingBeads, ["Flush_Square","Square_Flush"]):

                    // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD60Right');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD60Left');
                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SquareFlushFD30');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.SquareFlushFD30sectiondrawing');
                    $GlazingBeadsPadding = 0;

                    break;

                default:

                    // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');

                    $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.ChamferedFlushFD30');
                    $VisionPanelGlazingImageStructure_Frame=\Config::get('constants.base64Images.ChamferedFlushFD30sectiondrawing');
                    $GlazingBeadsPadding = 0;
                    break;
            }

            break;
    }

    $VisionPanelData = [];
    $VisionPanelData['GlazingBeadsPadding'] = $GlazingBeadsPadding;
    $VisionPanelData['VisionPanelGlazingImageStructure'] = $VisionPanelGlazingImageStructure;
    $VisionPanelData['VisionPanelGlazingImageRight'] = $VisionPanelGlazingImageRight;
    $VisionPanelData['VisionPanelGlazingImageLeft'] = $VisionPanelGlazingImageLeft;
    $VisionPanelData['VisionPanelGlazingImageStructure_Frame'] = $VisionPanelGlazingImageStructure_Frame;
    return $VisionPanelData;
}
