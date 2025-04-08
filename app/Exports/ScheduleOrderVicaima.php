<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\LippingSpecies;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\Company;
use Auth;

class ScheduleOrderVicaima implements FromCollection,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $vid;

    public function __construct($id,$vid) {
        $this->id = $id;
        $this->vid = $vid;
    }

    public function collection()
    {
        $quotationId = $this->id;
        $versionId = $this->vid;
        $quotaion = Quotation::where('id',$quotationId)->first();


        $item = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
        ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
        ->join('quotation','items.QuotationId','quotation.id')
        ->where('quotation_version_items.version_id',$versionId)->get();
        $SumDoorsetPrice = 0;
        $SumIronmongaryPrice = 0;
        $SumDoorQuantity = 0;
        $j = 1;
        $i = 0;
        $data = [];
        foreach($item as $items){
            $totalpriceperdoorset = $item[$i]->DoorsetPrice + $item[$i]->IronmongaryPrice;
            $SumDoorQuantity += $item[$i]->DoorQuantity;
            $SumDoorsetPrice += $item[$i]->DoorsetPrice;
            $SumIronmongaryPrice += $item[$i]->IronmongaryPrice;
            $configurableitems = '';
            if($item[$i]->configurableitems == 4){
                $configurableitems = 'Vicaima';
            }
            
            if($item[$i]->configurableitems == 5){
                $configurableitems = 'Seadec';
            }
            
            if($item[$i]->configurableitems == 6){
                $configurableitems = 'Deanta';
            }

        //Item master info
            $FrameOnOff = $item[$i]->FrameOnOff ?? 0;
            $Floor = $item[$i]->floor;
            $DoorNumber = $item[$i]->doorNumber;
            $location = $item[$i]->location;

        //Main options
            $configurableitems = $configurableitems;
            $DoorQuantity = $item[$i]->DoorQuantity;

            $FourSidedFrame = $item[$i]->FourSidedFrame;
            $LeafConstruction = $item[$i]->LeafConstruction;
            $DoorType = $item[$i]->DoorType;
            $FireRating = $item[$i]->FireRating;
            $DoorsetType = $item[$i]->DoorsetType;
            $SwingType = $item[$i]->SwingType;
            $LatchType = $item[$i]->LatchType;
            $Handing = $item[$i]->Handing;
            $OpensInwards = $item[$i]->OpensInwards;
            // $COC = $item[$i]->COC;
            $Tollerance = $item[$i]->Tollerance;
            $Dropseal = $item[$i]->Dropseal;
            $Undercut = $item[$i]->Undercut;
            $FloorFinish = $item[$i]->FloorFinish;
            $GAP = $item[$i]->GAP;
            $FrameThickness = $item[$i]->FrameThickness;
            $IronmongerySet = $item[$i]->IronmongerySet;
            $IronmongeryID = $item[$i]->IronmongeryID;


        //Door Dimensions & Door Leaf
            $DoorDimensions = $item[$i]->DoorDimensions;
            $DoorDimensions2 = $item[$i]->DoorDimensions2 ?? 0;
            $DoorDimensionsCode = $item[$i]->DoorDimensionsCode;
            $AdjustmentLeafWidth1 = $item[$i]->AdjustmentLeafWidth1;
            $AdjustmentLeafWidth2 = $item[$i]->AdjustmentLeafWidth2;
            $AdjustmentLeafHeightNoOP = $item[$i]->AdjustmentLeafHeightNoOP;
            $hinge1Location = $item[$i]->hinge1Location;
            $hinge2Location	 = $item[$i]->hinge2Location	;
            $hinge3Location = $item[$i]->hinge3Location;
            $hinge4Location = $item[$i]->hinge4Location;
            $hingeCenterCheck = $item[$i]->hingeCenterCheck;

            $SOHeight = $item[$i]->SOHeight;
            $SOWidth = $item[$i]->SOWidth;
            $SOWallThick = $item[$i]->SOWallThick;
            $LeafWidth1 = $item[$i]->LeafWidth1;
            $LeafWidth2 = $item[$i]->LeafWidth2;
            $LeafHeight = $item[$i]->LeafHeight;
            $LeafThickness = $item[$i]->LeafThickness;
            $DoorLeafFacing = $item[$i]->DoorLeafFacing;
            // $DoorLeafFacingValue = $item[$i]->DoorLeafFacingValue;
            $DoorLeafFinish = $item[$i]->DoorLeafFinish;
            // $DoorLeafFinishColor = $item[$i]->DoorLeafFinishColor;
            // $SheenLevel = $item[$i]->SheenLevel;
            $DecorativeGroves = $item[$i]->DecorativeGroves;
            $groovesNumber = $item[$i]->groovesNumber;
            // $GrooveLocation = $item[$i]->GrooveLocation;
            $GrooveWidth = $item[$i]->GrooveWidth;
            $GrooveDepth = $item[$i]->GrooveDepth;
            $MaxNumberOfGroove = $item[$i]->MaxNumberOfGroove;
            $NumberOfGroove = $item[$i]->NumberOfGroove;
            // $NumberOfVerticalGroove = $item[$i]->NumberOfVerticalGroove;
            // $NumberOfHorizontalGroove = $item[$i]->NumberOfHorizontalGroove;
            $DecorativeGrovesLeaf2 = $item[$i]->DecorativeGrovesLeaf2;
            $IsSameAsDecorativeGroves1 = $item[$i]->IsSameAsDecorativeGroves1;
            $GroovesNumberLeaf2 = $item[$i]->GroovesNumberLeaf2;
            $GrooveWidthLeaf2 = $item[$i]->GrooveWidthLeaf2;
            $GrooveDepthLeaf2 = $item[$i]->GrooveDepthLeaf2;
            $MaxNumberOfGrooveLeaf2 = $item[$i]->MaxNumberOfGrooveLeaf2;
            $NumberOfGrooveLeaf2 = $item[$i]->NumberOfGrooveLeaf2;
            // $NumberOfVerticalGrooveLeaf2 = $item[$i]->NumberOfVerticalGrooveLeaf2;
            // $NumberOfHorizontalGrooveLeaf2 = $item[$i]->NumberOfHorizontalGrooveLeaf2;

            //Vision Panel
            $Leaf1VisionPanel = $item[$i]->Leaf1VisionPanel;
            $Leaf1VisionPanelShape = $item[$i]->Leaf1VisionPanelShape;
            $VisionPanelQuantity = $item[$i]->VisionPanelQuantity;
            $AreVPsEqualSizesForLeaf1 = $item[$i]->AreVPsEqualSizesForLeaf1;
            $DistanceFromtopOfDoor = $item[$i]->DistanceFromtopOfDoor;
            $DistanceFromTheEdgeOfDoor = $item[$i]->DistanceFromTheEdgeOfDoor;
            $DistanceBetweenVPs = $item[$i]->DistanceBetweenVPs;
            $Leaf1VPWidth = $item[$i]->Leaf1VPWidth;
            $Leaf1VPHeight1 = $item[$i]->Leaf1VPHeight1;
            $Leaf1VPHeight2 = $item[$i]->Leaf1VPHeight2;
            $Leaf1VPHeight3 = $item[$i]->Leaf1VPHeight3;
            $Leaf1VPHeight4 = $item[$i]->Leaf1VPHeight4;
            $Leaf1VPHeight5 = $item[$i]->Leaf1VPHeight5;
            $Leaf1VPAreaSizem2 = $item[$i]->Leaf1VPAreaSizem2;
            $Leaf2VisionPanel = $item[$i]->Leaf2VisionPanel;
            $sVPSameAsLeaf1 = $item[$i]->sVPSameAsLeaf1;
            $Leaf2VisionPanelQuantity = $item[$i]->Leaf2VisionPanelQuantity;
            $AreVPsEqualSizesForLeaf2 = $item[$i]->AreVPsEqualSizesForLeaf2;
            $DistanceFromTopOfDoorForLeaf2 = $item[$i]->DistanceFromTopOfDoorForLeaf2;
            $DistanceFromTheEdgeOfDoorforLeaf2 = $item[$i]->DistanceFromTheEdgeOfDoorforLeaf2;
            $DistanceBetweenVp = $item[$i]->DistanceBetweenVp;
            $Leaf2VPWidth = $item[$i]->Leaf2VPWidth;
            $Leaf2VPHeight1 = $item[$i]->Leaf2VPHeight1;
            $Leaf2VPHeight2 = $item[$i]->Leaf2VPHeight2;
            $Leaf2VPHeight3 = $item[$i]->Leaf2VPHeight3;
            $Leaf2VPHeight4 = $item[$i]->Leaf2VPHeight4;
            $Leaf2VPHeight5 = $item[$i]->Leaf2VPHeight5;
            $GlassIntegrity = $item[$i]->GlassIntegrity;
            $GlassType = $item[$i]->GlassType;
            $GlassThickness = $item[$i]->GlassThickness;
            $GlazingSystems = $item[$i]->GlazingSystems;
            $GlazingSystemThickness = $item[$i]->GlazingSystemThickness;
            $GlazingBeads = $item[$i]->GlazingBeads;
            $GlazingBeadsThickness = $item[$i]->GlazingBeadsThickness;
            $glazingBeadsWidth = $item[$i]->glazingBeadsWidth;
            $glazingBeadsHeight = $item[$i]->glazingBeadsHeight;
            $glazingBeadsFixingDetail = $item[$i]->glazingBeadsFixingDetail;
            $GlazingBeadSpecies = lippingSpeciesName($item[$i]->GlazingBeadSpecies);
            //Frame
            $FrameMaterial = lippingSpeciesName($item[$i]->FrameMaterial);
            $FrameType = $item[$i]->FrameType;
            $PlantonStopWidth = $item[$i]->PlantonStopWidth;
            $PlantonStopHeight = $item[$i]->PlantonStopHeight;
            $RebatedWidth = $item[$i]->RebatedWidth;
            $RebatedHeight = $item[$i]->RebatedHeight;
            $ScallopedWidth = $item[$i]->ScallopedWidth;
            $ScallopedHeight = $item[$i]->ScallopedHeight;
            $FrameWidth = $item[$i]->FrameWidth;
            $FrameHeight = $item[$i]->FrameHeight;
            $FrameDepth = $item[$i]->FrameDepth;
            $FrameFinish = $item[$i]->FrameFinish;
            $FrameFinishColor = $item[$i]->FrameFinishColor;
            $ExtLiner = $item[$i]->ExtLiner;
            $DoorFrameConstruction = $item[$i]->DoorFrameConstruction;
            $ExtLinerValue = $item[$i]->ExtLinerValue;
            $extLinerSize = $item[$i]->extLinerSize;
            $ExtLinerThickness = $item[$i]->ExtLinerThickness;
            $SpecialFeatureRefs = $item[$i]->SpecialFeatureRefs;

            //Over Panel
            $Overpanel = $item[$i]->Overpanel;
            $OPWidth = $item[$i]->OPWidth;
            $OPHeigth = $item[$i]->OPHeigth;
            $OpBeadThickness = $item[$i]->OpBeadThickness;
            $OpBeadHeight = $item[$i]->OpBeadHeight;
            $OPTransom = $item[$i]->OPTransom;
            $TransomThickness = $item[$i]->TransomThickness;
            $opGlassIntegrity = $item[$i]->opGlassIntegrity;
            $OPGlassType = $item[$i]->OPGlassType;
            // new changes 15-07-2024
            $OPGlassThickness = $item[$i]->OPGlassThickness;
            $opglazingSystemsvalue = $item[$i]->OPGlazingSystems;
            $OPGlazingSystemsThickness = $item[$i]->OPGlazingSystemsThickness;
            $OPGlazingBeadsThickness = $item[$i]->OPGlazingBeadsThickness;
            $OPGlazingBeadsHeight = $item[$i]->OPGlazingBeadsHeight; // confusion
            $OPGlazingBeadsFixingDetail = $item[$i]->OPGlazingBeadsFixingDetail;
            //end
            $OPGlazingBeads = $item[$i]->OPGlazingBeads;
            $OPGlazingBeadSpecies = lippingSpeciesName($item[$i]->OPGlazingBeadSpecies);

            //Side Light
            $SideLight1 = $item[$i]->SideLight1;
            $SideLight1GlassType = $item[$i]->SideLight1GlassType;
            $BeadingType = $item[$i]->BeadingType;
            $SL1GlazingBeadSpecies = lippingSpeciesName($item[$i]->SL1GlazingBeadSpecies);
            $SL1Width = $item[$i]->SL1Width;
            $SL1Height = $item[$i]->SL1Height;
            $SlBeadThickness = $item[$i]->SlBeadThickness;
            $SlBeadHeight = $item[$i]->SlBeadHeight;
            $SL1Depth = $item[$i]->SL1Depth;
            $SL1Transom = $item[$i]->SL1Transom;
            // new changes 15-07-2024
            $SL1GlassThickness = $item[$i]->SideLight1GlassThickness;
            $SL1GlazingSystems = $item[$i]->SideLight1GlazingSystems;
            $SL1GlazingSystemsThickness = $item[$i]->SideLight1GlazingSystemsThickness;
            $SL1GlazingBeadsThickness = $item[$i]->SideLight1GlazingBeadsThickness;
            $SL1GlazingBeadsWidth = $item[$i]->SideLight1GlazingBeadsWidth;
            $SL1GlazingBeadsFixingDetail = $item[$i]->SideLight1GlazingBeadsFixingDetail;
            //end
            $SideLight2 = $item[$i]->SideLight2;
            $DoYouWantToCopySameAsSL1 = $item[$i]->DoYouWantToCopySameAsSL1;
            $SideLight2GlassType = $item[$i]->SideLight2GlassType;
            $SideLight2BeadingType = $item[$i]->SideLight2BeadingType;
            $SideLight2GlazingBeadSpecies = lippingSpeciesName($item[$i]->SideLight2GlazingBeadSpecies);
            $SL2Width = $item[$i]->SL2Width;
            $SL2Height = $item[$i]->SL2Height;
            $SL2Depth = $item[$i]->SL2Depth;
            $SL2Transom = $item[$i]->SL2Transom;
            $SLtransomHeightFromTop = $item[$i]->SLtransomHeightFromTop;
            $SLtransomThickness = $item[$i]->SLtransomThickness;
             // new changes 15-07-2024
             $SL2GlassThickness = $item[$i]->SideLight2GlassThickness;
             $SL2GlazingSystems = $item[$i]->SideLight2GlazingSystems;
             $SL2GlazingSystemsThickness = $item[$i]->SideLight2GlazingSystemsThickness;
             $SL2GlazingBeadsThickness = $item[$i]->SideLight2GlazingBeadsThickness;
             $SL2GlazingBeadsWidth = $item[$i]->SideLight2GlazingBeadsWidth;
             $SL2GlazingBeadsFixingDetail = $item[$i]->SideLight2GlazingBeadsFixingDetail;
             //end

            //Lipping & Intumescent
            $LippingType = $item[$i]->LippingType;
            $LippingThickness = $item[$i]->LippingThickness;
            $LippingSpecies = lippingSpeciesName($item[$i]->LippingSpecies);
            $MeetingStyle = $item[$i]->MeetingStyle;
            $ScallopedLippingThickness = $item[$i]->ScallopedLippingThickness;
            $FlatLippingThickness = $item[$i]->FlatLippingThickness;
            $RebatedLippingThickness = $item[$i]->RebatedLippingThickness;
            $CoreWidth1 = $item[$i]->CoreWidth1;
            $CoreWidth2 = $item[$i]->CoreWidth2;
            $CoreHeight = $item[$i]->CoreHeight;
            $IntumescentLeapingSealType = $item[$i]->IntumescentLeapingSealType;
            $IntumescentLeapingSealLocation = $item[$i]->IntumescentLeapingSealLocation;
            $IntumescentLeapingSealColor = $item[$i]->IntumescentLeapingSealColor;
            $IntumescentLeapingSealArrangement = $item[$i]->IntumescentLeapingSealArrangement;
            $intumescentSealMeetingEdges = $item[$i]->intumescentSealMeetingEdges;

            //Acoustics
            $Accoustics = $item[$i]->Accoustics;
            $rWdBRating = $item[$i]->rWdBRating;
            $perimeterSeal1 = $item[$i]->perimeterSeal1;
            $perimeterSeal2 = $item[$i]->perimeterSeal2;
            $thresholdSeal1 = $item[$i]->thresholdSeal1;
            $thresholdSeal2 = $item[$i]->thresholdSeal2;
            $AccousticsMeetingStiles = $item[$i]->AccousticsMeetingStiles;

            //Architrave
            $Architrave = $item[$i]->Architrave;
            $ArchitraveMaterial = lippingSpeciesName($item[$i]->ArchitraveMaterial);
            $ArchitraveType = $item[$i]->ArchitraveType;
            $ArchitraveWidth = $item[$i]->ArchitraveWidth;
            $ArchitraveThickness = $item[$i]->ArchitraveHeight;
            $ArchitraveFinish = $item[$i]->ArchitraveFinish;
            $ArchitraveFinishColor = $item[$i]->ArchitraveFinishColor;
            $ArchitraveSetQty = $item[$i]->ArchitraveSetQty;

            $DoorsetPrice = $item[$i]->DoorsetPrice;
            $IronmongaryPrice = $item[$i]->IronmongaryPrice;
            $totalpriceperdoorset = $totalpriceperdoorset;

            $data[] = [
                $j,
                $configurableitems,
                $FrameOnOff,
                $Floor,
                $DoorNumber,
                $location,
                $DoorQuantity,
                $FourSidedFrame,
                $LeafConstruction,
                $DoorType,
                $FireRating,
                $DoorsetType,
                $SwingType,
                $LatchType,
                $Handing,
                $OpensInwards,
                $Tollerance,
                $Dropseal,
                $Undercut,
                $FloorFinish,
                $GAP,
                $FrameThickness,
                $IronmongerySet,
                $IronmongeryID,
                $DoorLeafFacing,
                $DoorLeafFinish,
                $DoorDimensions,
                $DoorDimensions2,
                $DoorDimensionsCode,
                $SOHeight,
                $SOWidth,
                $SOWallThick,
                $LeafWidth1,
                $AdjustmentLeafWidth1,
                $LeafWidth2,
                $AdjustmentLeafWidth2,
                $LeafHeight,
                $AdjustmentLeafHeightNoOP,
                $LeafThickness,

                $hinge1Location,
                $hinge2Location,
                $hinge3Location,
                $hinge4Location,
                $hingeCenterCheck,
                $DecorativeGroves,
                $groovesNumber,
                $GrooveWidth,
                $GrooveDepth,
                $MaxNumberOfGroove,
                $NumberOfGroove,
                $DecorativeGrovesLeaf2,
                $IsSameAsDecorativeGroves1,
                $GroovesNumberLeaf2,
                $GrooveWidthLeaf2,
                $GrooveDepthLeaf2,
                $MaxNumberOfGrooveLeaf2,
                $NumberOfGrooveLeaf2,
                $Leaf1VisionPanel,
                $Leaf1VisionPanelShape,
                $VisionPanelQuantity,
                $AreVPsEqualSizesForLeaf1,
                $DistanceFromtopOfDoor,
                $DistanceFromTheEdgeOfDoor,
                $DistanceBetweenVPs,
                $Leaf1VPWidth,
                $Leaf1VPHeight1,
                $Leaf1VPHeight2,
                $Leaf1VPHeight3,
                $Leaf1VPHeight4,
                $Leaf1VPHeight5,
                $Leaf1VPAreaSizem2,
                $Leaf2VisionPanel,
                $sVPSameAsLeaf1,
                $Leaf2VisionPanelQuantity,
                $AreVPsEqualSizesForLeaf2,
                $DistanceFromTopOfDoorForLeaf2,
                $DistanceFromTheEdgeOfDoorforLeaf2,
                $DistanceBetweenVp,
                $Leaf2VPWidth,
                $Leaf2VPHeight1,
                $Leaf2VPHeight2,
                $Leaf2VPHeight3,
                $Leaf2VPHeight4,
                $Leaf2VPHeight5,
                $GlassIntegrity,
                $GlassType,
                $GlassThickness,
                $GlazingSystems,
                $GlazingSystemThickness,
                $GlazingBeads,
                $GlazingBeadsThickness,
                $glazingBeadsWidth,
                $glazingBeadsHeight,
                $glazingBeadsFixingDetail,
                $GlazingBeadSpecies,
                $FrameMaterial,
                $FrameType,
                $PlantonStopWidth,
                $PlantonStopHeight,
                $RebatedWidth,
                $RebatedHeight,
                $ScallopedWidth,
                $ScallopedHeight,
                $FrameWidth,
                $FrameHeight,
                $FrameDepth,
                $FrameFinish,
                $FrameFinishColor,
                $ExtLiner,
                $DoorFrameConstruction,
                $ExtLinerValue,
                $extLinerSize,
                $ExtLinerThickness,
                $SpecialFeatureRefs,
                $Overpanel,
                $OPWidth,
                $OPHeigth,
                $OpBeadThickness,
                $OpBeadHeight,
                $OPTransom,
                $TransomThickness,
                $opGlassIntegrity,
                $OPGlassType,
                //
                $OPGlassThickness,
                $opglazingSystemsvalue,
                $OPGlazingSystemsThickness,
                //
                $OPGlazingBeads,
                //
                $OPGlazingBeadsThickness,
                $OPGlazingBeadsHeight, // confusion
                $OPGlazingBeadsFixingDetail,
                //
                $OPGlazingBeadSpecies,
                $SideLight1,
                $SideLight1GlassType,
                $SL1GlassThickness,
                $SL1GlazingSystems,
                $SL1GlazingSystemsThickness,
                $BeadingType,
                $SL1GlazingBeadsThickness,
                $SL1GlazingBeadsWidth,
                $SL1GlazingBeadsFixingDetail,
                $SL1GlazingBeadSpecies,
                $SL1Width,
                $SL1Height,
                $SlBeadThickness,
                $SlBeadHeight,
                $SL1Depth,
                $SL1Transom,
                $SideLight2,
                $DoYouWantToCopySameAsSL1,
                $SideLight2GlassType,
                $SL2GlassThickness,
                $SL2GlazingSystems,
                $SL2GlazingSystemsThickness,
                $SideLight2BeadingType,
                $SL2GlazingBeadsThickness,
                $SL2GlazingBeadsWidth,
                $SL2GlazingBeadsFixingDetail,
                $SideLight2GlazingBeadSpecies,
                $SL2Width,
                $SL2Height,
                $SL2Depth,
                $SL2Transom,
                $SLtransomHeightFromTop,
                $SLtransomThickness,
                $LippingType,
                $LippingThickness,
                $LippingSpecies,
                $MeetingStyle,
                $ScallopedLippingThickness,
                $FlatLippingThickness,
                $RebatedLippingThickness,
                $CoreWidth1,
                $CoreWidth2,
                $CoreHeight,
                $IntumescentLeapingSealType,
                $IntumescentLeapingSealLocation,
                $IntumescentLeapingSealColor,
                $IntumescentLeapingSealArrangement,
                $intumescentSealMeetingEdges,
                $Accoustics,
                $rWdBRating,
                $perimeterSeal1,
                $perimeterSeal2,
                $thresholdSeal1,
                $thresholdSeal2,
                $AccousticsMeetingStiles,
                $Architrave,
                $ArchitraveMaterial,
                $ArchitraveType,
                $ArchitraveWidth,
                $ArchitraveThickness,
                $ArchitraveFinish,
                $ArchitraveFinishColor,
                $ArchitraveSetQty,
                $DoorsetPrice,
                $IronmongaryPrice,
                $totalpriceperdoorset
            ];
            $i++;
            $j++;
        }
        
        $Alltotalpriceperdoorset = $SumDoorsetPrice + $SumIronmongaryPrice;
        $footData = [
            '',
            '',
            '',
            '',
            '',
            '',
            $SumDoorQuantity,
            '','', '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
            $SumDoorsetPrice,
            $SumIronmongaryPrice,
            $Alltotalpriceperdoorset,
        ];

        $allData = [$data,$footData];

        return collect($allData);
        // return collect($data);
    }
    
    public function headings(): array
    {
        $a = [
            'S.No',
            'Configurableitems',
            'Frame On/Off ',
            'Floor ',
            'Door Number ',
            'location ',
            'Door Quantity ',
            'Four Sided Frame',
            'Leaf Type ',
            'Door Type ',
            'Fire Rating ',
            'Doorset Type ',
            'Swing Type ',
            'Latch Type ',
            'Handing ',
            'Pull Towards ',
            'Tollerance ',
            'Dropseal ',
            'Undercut ',
            'Floor Finish ',
            'GAP ',
            'Frame Thickness ',
            'Ironmongery Set ',
            'Ironmongery ID ',
            'DoorLeaf Facing ',
            'DoorLeaf Finish ',
            'Door Dimension Id ',
            'Door Dimension(leafandhalf) Id ',
            'Door Dimensions ',
            'SOHeight ',
            'SOWidth ',
            'SOWallThick ',
            'LeafWidth1 ',
            'Leaf Width 1 Adjustment ',
            'LeafWidth2 ',
            'Leaf Width 2 Adjustment ',
            'LeafHeight ',
            'Leaf Height Adjustment ',
            'Leaf Thickness ',
            'Hinge1 Location',
            'Hinge2 Location',
            'Hinge3 Location',
            'Hinge4 Location',
            'Hinge Center Check',
            'Decorative Groves ',
            'Groove Icon ',
            'Groove Width ',
            'Groove Depth ',
            'Max Number Of Groove ',
            'Number Of Groove ',
            'Decorative Groves Leaf2',
            'Is Same As Decorative Groves 1',
            'Groove Icon Leaf2',
            'Groove Width Leaf2',
            'Groove Depth Leaf2',
            'Max Number Of Groove Leaf2',
            'Number Of Groove Leaf2',
            'Leaf1VisionPanel ',
            'Leaf1VisionPanel Shape ',
            'VisionPanel Quantity ',
            'Are VPs Equal Sizes For Leaf1 ',
            'Distance From top Of Door ',
            'Distance From The Edge Of Door ',
            'Distance Between VPs ',
            'Leaf1VPWidth ',
            'Leaf1VPHeight1 ',
            'Leaf1VPHeight2 ',
            'Leaf1VPHeight3 ',
            'Leaf1VPHeight4 ',
            'Leaf1VPHeight5 ',
            'Leaf1VPAreaSizem2 ',
            'Leaf2VisionPanel ',
            'sVPSameAsLeaf1 ',
            'Leaf2 VisionPanel Quantity ',
            'Are VPs Equal Sizes For Leaf2 ',
            'Distance From Top Of Door For Leaf2 ',
            'Distance From The Edge Of Door for Leaf2 ',
            'Distance Between Vp ',
            'Leaf2VPWidth ',
            'Leaf2VPHeight1 ',
            'Leaf2VPHeight2 ',
            'Leaf2VPHeight3 ',
            'Leaf2VPHeight4 ',
            'Leaf2VPHeight5 ',
            'Glass Integrity ',
            'Glass Type ',
            'Glass Thickness ',
            'Glazing Systems ',
            'Glazing System Thickness ',
            'Glazing Beads ',
            'Glazing Beads Thickness ',
            'glazing Beads Width ',
            'glazing Beads Height ',
            'glazing Beads Fixing Detail ',
            'Glazing Bead Species ',
            'Frame Material ',
            'Frame Type ',
            'Plant on Stop Width ',
            'Plant on Stop Height ',
            'Rebated Width ',
            'Rebated Height ',
            'Scalloped Width ',
            'Scalloped Depth ',
            'Frame Width ',
            'Frame Height ',
            'Frame Depth ',
            'Frame Finish ',
            'Frame Finish Color ',
            'ExtLiner ',
            'Door Frame Construction ',
            'ExtLiner Value ',
            'extLiner Size ',
            'ExtLiner Thickness ',
            'Special Feature Refs ',
            'Overpanel ',
            'OPWidth ',
            'OPHeigth ',
            'OpBead Thickness ',
            'OpBead Height ',
            'OP Transom ',
            'Transom Thickness ',
            'opGlass Integrity ',
            'OPGlass Type ',
            'OPGlass Thickness',
            'OPGlass Glazing Systems',
            'OPGlass Glazing System Thickness',
            'OPGlazing Beads ',
            'OPGlazing Glazing Beads Thickness',
            'OPGlazing Glazing Beads Width', // confusion
            'OPGlazing Glazing Bead Fixing Detail',
            'OPGlazing Bead Species ',
            'SideLight1 ',
            'SideLight1 Glass Type ',
            'Side Light 1 Glass Thickness',
            'Side Light 1 Glazing Systems',
            'Side Light 1 Glazing System Thickness',
            'Beading Type ',
            'Side Light 1 Glazing Beads Thickness',
            'Side Light 1 Glazing Beads Width',
            'Side Light 1 Glazing Bead Fixing Detail',
            'SL1 Glazing Bead Species ',
            'SL1Width ',
            'SL1Height ',
            'SlBead Thickness ',
            'SlBead Height ',
            'SL1Depth ',
            'SL1Transom ',
            'SideLight2 ',
            'Do You Want To Copy Same As SL1 ',
            'SideLight2 Glass Type ',
            'Side Light 2 Glass Thickness',
            'Side Light 2 Glazing Systems',
            'Side Light 2 Glazing System Thickness',
            'SideLight2 Beading Type ',
            'Side Light 2 Glazing Beads Thickness',
            'Side Light 2 Glazing Beads Width',
            'Side Light 2 Glazing Bead Fixing Detail',
            'SideLight2 Glazing Bead Species ',
            'SL2Width ',
            'SL2Height ',
            'SL2Depth ',
            'SL2Transom ',
            'SLtransom Heigh From Top ',
            'SLtransom Thickness ',
            'Lipping Type ',
            'Lipping Thickness ',
            'Lipping Species ',
            'Meeting Style ',
            'Scalloped Lipping Thickness ',
            'Flat Lipping Thickness ',
            'Rebated Lipping Thickness ',
            'CoreWidth1 ',
            'CoreWidth2 ',
            'CoreHeight ',
            'Intumescent Leaping Seal Type ',
            'Intumescent Leaping Seal Location ',
            'Intumescent Leaping Seal Color ',
            'Intumescent Leaping Seal Arrangement ',
            'Intumescent Seal Meeting Edges ',
            'Accoustics ',
            'rWdBRating ',
            'perimeter Seal1 ',
            'perimeter Seal2 ',
            'threshold Seal1 ',
            'threshold Seal2 ',
            'Accoustics Meeting Stiles ',
            'Architrave ',
            'Architrave Material ',
            'Architrave Type ',
            'Architrave Width ',
            'Architrave Thickness ',
            'Architrave Finish ',
            'Architrave Finish Color ',
            'Architrave Set Qty ',
            'Doorset Price ',
            'Ironmongary Price ',
            'Total price per doorset'
        ];


        $d = [$a];
        return $d;







        // $ProjectDetails = [
        //     [
        //       'Project' => 'Project'
        //     ],
        //     [
        //       'Customer' => 'Customer'
        //     ]
        // ];
        // $d = [$ProjectDetails,     $a];



    }
    
    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange = 'A1:GO1'; // All headers
                // $cellRange->setFontWeight('bold');
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color'=> '#000000'
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],

                ];
                $event->sheet->getStyle("A1:GO1")->getAlignment()->setTextRotation(90)->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(10)->setRowHeight(60);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
