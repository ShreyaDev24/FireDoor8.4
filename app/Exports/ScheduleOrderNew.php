<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\Items;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\LippingSpecies;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\Company;
use Auth;

class ScheduleOrderNew implements FromCollection,WithHeadings,WithEvents
{
    public function __construct(
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $id,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $vid
    )
    {
    }

    public function collection()
    {
        $quotationId = $this->id;
        $versionId = $this->vid;
        $quotaion = Quotation::where('id',$quotationId)->first();


        // $item = Items::join('item_master', 'item_master.itemID', '=', 'items.itemId')
        //     ->where('QuotationId', $quotationId)
        //     ->where('VersionId', $versionId)
        //     ->get();
        $item = Items::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                ->join('quotation','items.QuotationId','quotation.id')
                ->where('quotation_version_items.version_id',$versionId)
                ->get();
        $SumDoorsetPrice = 0;
        $SumIronmongaryPrice = 0;
        $SumDoorQuantity = 0;
        $j = 1;
        $i = 0;
        $data = [];
        foreach($item as $items){
            $DoorsetPrice = ($item[$i]->AdjustPrice != 0) ? $item[$i]->AdjustPrice : $item[$i]->DoorsetPrice;
            $totalpriceperdoorset = $DoorsetPrice + $item[$i]->IronmongaryPrice;
            $SumDoorQuantity += $item[$i]->DoorQuantity;
            $SumDoorsetPrice += $DoorsetPrice;
            $SumIronmongaryPrice += $item[$i]->IronmongaryPrice;
            $configurableitems = '';
            if($item[$i]->configurableitems == 1){
                $configurableitems = 'Streboard';
            }elseif($item[$i]->configurableitems == 2){
                $configurableitems = 'Halspan';
            }elseif($item[$i]->configurableitems == 3){
                $configurableitems = 'Norma';
            }elseif($item[$i]->configurableitems == 4){
                $configurableitems = 'Vicaima';
            }elseif($item[$i]->configurableitems == 5){
                $configurableitems = 'Seadec';
            }elseif($item[$i]->configurableitems == 6){
                $configurableitems = 'Deanta';
            }elseif($item[$i]->configurableitems == 7){
                $configurableitems = 'Flamebreak';
            }elseif($item[$i]->configurableitems == 8){
                $configurableitems = 'Stredor';
            }

        //Item master info
            $FrameOnOff = $item[$i]->FrameOnOff ?? 0;
            $Floor = $item[$i]->floor;
            $DoorNumber = $item[$i]->doorNumber;
            $location = $item[$i]->location;

        //Main options
            $configurableitems = $configurableitems;
            $DoorQuantity = $item[$i]->DoorQuantity;
            $IntumescentLeafType = $item[$i]->IntumescentLeafType;

            $Dropseal = $item[$i]->Dropseal ?? 0;
            $DoorType = $item[$i]->DoorType;
            $FireRating = $item[$i]->FireRating;
            $DoorsetType = $item[$i]->DoorsetType;
            $SwingType = $item[$i]->SwingType;
            $LatchType = $item[$i]->LatchType;
            $Handing = $item[$i]->Handing;
            $OpensInwards = $item[$i]->OpensInwards;
            $COC = $item[$i]->COC;
            $Tollerance = $item[$i]->Tollerance;
            $FourSidedFrame = $item[$i]->FourSidedFrame ?? 0;
            $Undercut = $item[$i]->Undercut;
            $FloorFinish = $item[$i]->FloorFinish;
            $GAP = $item[$i]->GAP;
            $FrameThickness = $item[$i]->FrameThickness;
            $IronmongerySet = $item[$i]->IronmongerySet;
            $FolderId = $item[$i]->FolderId;
            $IronmongeryID = $item[$i]->IronmongeryID;


        //Door Dimensions & Door Leaf
            $SOHeight = $item[$i]->SOHeight;
            $SOWidth = $item[$i]->SOWidth;
            $SOWallThick = $item[$i]->SOWallThick;
            $LeafWidth1 = $item[$i]->LeafWidth1;
            $LeafWidth2 = $item[$i]->LeafWidth2;
            $LeafHeight = $item[$i]->LeafHeight;
            $LeafThickness = $item[$i]->LeafThickness;
            $DoorLeafFacing = $item[$i]->DoorLeafFacing;
            $DoorLeafFacingValue = $item[$i]->DoorLeafFacingValue;
            $DoorLeafFinish = $item[$i]->DoorLeafFinish;
            $DoorLeafFinishColor = $item[$i]->DoorLeafFinishColor;
            $SheenLevel = $item[$i]->SheenLevel;
            $DecorativeGroves = $item[$i]->DecorativeGroves;
            $GrooveLocation = $item[$i]->GrooveLocation;
            $GrooveWidth = $item[$i]->GrooveWidth;
            $GrooveDepth = $item[$i]->GrooveDepth;
            $MaxNumberOfGroove = $item[$i]->MaxNumberOfGroove;
            $NumberOfGroove = $item[$i]->NumberOfGroove;
            $NumberOfVerticalGroove = $item[$i]->NumberOfVerticalGroove;
            $NumberOfHorizontalGroove = $item[$i]->NumberOfHorizontalGroove;
            $DecorativeGrovesLeaf2 = $item[$i]->DecorativeGrovesLeaf2;
            $GrooveLocationLeaf2 = $item[$i]->GrooveLocationLeaf2;
            $IsSameAsDecorativeGroves1 = $item[$i]->IsSameAsDecorativeGroves1;
            $GrooveWidthLeaf2 = $item[$i]->GrooveWidthLeaf2;
            $GrooveDepthLeaf2 = $item[$i]->GrooveDepthLeaf2;
            $MaxNumberOfGrooveLeaf2 = $item[$i]->MaxNumberOfGrooveLeaf2;
            $NumberOfGrooveLeaf2 = $item[$i]->NumberOfGrooveLeaf2;
            $NumberOfVerticalGrooveLeaf2 = $item[$i]->NumberOfVerticalGrooveLeaf2;
            $NumberOfHorizontalGrooveLeaf2 = $item[$i]->NumberOfHorizontalGrooveLeaf2;

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
            $glazingBeadsWidth = $item[$i]->glazingBeadsHeight;
            $glazingBeadsHeight = $item[$i]->glazingBeadsThickness;
            $glazingBeadsFixingDetail = $item[$i]->glazingBeadsFixingDetail;
            $GlazingBeadSpecies = lippingSpeciesName($item[$i]->GlazingBeadSpecies);
            //Frame
            $FrameMaterial = lippingSpeciesName($item[$i]->FrameMaterial);
            $FrameType = $item[$i]->FrameType;
            $PlantonStopWidth = $item[$i]->PlantonStopWidth;
            $PlantonStopHeight = $item[$i]->PlantonStopHeight;
            $ScallopedWidth = $item[$i]->ScallopedWidth;
            $ScallopedHeight = $item[$i]->ScallopedHeight;
            $RebatedWidth = $item[$i]->RebatedWidth;
            $RebatedHeight = $item[$i]->RebatedHeight;
            $FrameWidth = $item[$i]->FrameWidth;
            $FrameHeight = $item[$i]->FrameHeight;
            $HeadFrameThickness = $item[$i]->HeadFrameThickness ?? $item[$i]->FrameThickness;
            $BottomFrameThickness  = $item[$i]->BottomFrameThickness ?? $item[$i]->FrameThickness;
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
            $opGlassIntegrity = $item[$i]->opGlassIntegrity;
            $OPGlassType = $item[$i]->OPGlassType;
            $OPGlassThickness = $item[$i]->OPGlassThickness;
            $opglazingSystemsvalue = $item[$i]->OPGlazingSystems;
            $OPGlazingSystemsThickness = $item[$i]->OPGlazingSystemsThickness;
            $OPGlazingBeads = $item[$i]->OPGlazingBeads;
            $OPGlazingBeadsThickness = $item[$i]->OPGlazingBeadsThickness;
            $OPGlazingBeadsHeight = $item[$i]->OPGlazingBeadsHeight; // confusion
            $OPGlazingBeadsFixingDetail = $item[$i]->OPGlazingBeadsFixingDetail;
            $OPGlazingBeadSpecies = lippingSpeciesName($item[$i]->OPGlazingBeadSpecies);

            //Side Light
            $SideLight1 = $item[$i]->SideLight1;
            $SL1GlassIntegrity = $item[$i]->SL1GlassIntegrity;
            $SideLight1GlassType = $item[$i]->SideLight1GlassType;
            $SL1GlassThickness = $item[$i]->SideLight1GlassThickness;
            $SL1GlazingSystems = $item[$i]->SideLight1GlazingSystems;
            $SL1GlazingSystemsThickness = $item[$i]->SideLight1GlazingSystemsThickness;
            $BeadingType = $item[$i]->BeadingType;
            $SideLight1FrameThickness = $item[$i]->SideLight1FrameThickness;
            $SL1GlazingBeadsFixingDetail = $item[$i]->SideLight1GlazingBeadsFixingDetail;
            $SL1GlazingBeadSpecies = lippingSpeciesName($item[$i]->SL1GlazingBeadSpecies);
            $SL1Width = $item[$i]->SL1Width;
            $SL1Height = $item[$i]->SL1Height;
            $SlBeadThickness = $item[$i]->SlBeadThickness;
            $SlBeadHeight = $item[$i]->SlBeadHeight;
            $SL1Depth = $item[$i]->SL1Depth;
            $SL1Transom = $item[$i]->SL1Transom;
            $SL1TransomDepth = $item[$i]->SL1TransomDepth;
            $SL1transomThickness = $item[$i]->SL1transomThickness;

            $SideLight2 = $item[$i]->SideLight2;
            $DoYouWantToCopySameAsSL1 = $item[$i]->DoYouWantToCopySameAsSL1;
            $SL2GlassIntegrity = $item[$i]->SL2GlassIntegrity;
            $SideLight2GlassType = $item[$i]->SideLight2GlassType;
            $SL2GlassThickness = $item[$i]->SideLight2GlassThickness;
            $SL2GlazingSystems = $item[$i]->SideLight2GlazingSystems;
            $SL2GlazingSystemsThickness = $item[$i]->SideLight2GlazingSystemsThickness;
            $SideLight2BeadingType = $item[$i]->SideLight2BeadingType;
            $SideLight2FrameThickness = $item[$i]->SideLight2FrameThickness;
            $SL2GlazingBeadsFixingDetail = $item[$i]->SideLight2GlazingBeadsFixingDetail;
            $SideLight2GlazingBeadSpecies = lippingSpeciesName($item[$i]->SideLight2GlazingBeadSpecies);
            $SL2Width = $item[$i]->SL2Width;
            $SL2Height = $item[$i]->SL2Height;
            $SL2Depth = $item[$i]->SL2Depth;
            $SL2Transom = $item[$i]->SL2Transom;
            $SL2transomThickness = $item[$i]->SL2transomThickness;
            $SL2TransomDepth = $item[$i]->SL2TransomDepth;
            $SLtransomHeightFromTop = $item[$i]->SLtransomHeightFromTop;

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
            // $thresholdSeal1 = $item[$i]->thresholdSeal1;
            // $thresholdSeal2 = $item[$i]->thresholdSeal2;
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

            $IronmongaryPrice = $item[$i]->IronmongaryPrice;
            $totalpriceperdoorset = $totalpriceperdoorset;

            $data[] = [
                $j,
                $configurableitems,
                $IntumescentLeafType,
                $FrameOnOff,
                $Floor,
                $DoorNumber,
                $location,
                $DoorQuantity,
                $FourSidedFrame,
                $DoorType,
                $FireRating,
                $DoorsetType,
                $SwingType,
                $LatchType,
                $Handing,
                $OpensInwards,
                $COC,
                $Tollerance,
                $Dropseal,
                $Undercut,
                $FloorFinish,
                $GAP,
                $FrameThickness,
                $IronmongerySet,
                $FolderId,
                $IronmongeryID,
                $SOHeight,
                $SOWidth,
                $SOWallThick,
                $LeafWidth1,
                $LeafWidth2,
                $LeafHeight,
                $LeafThickness,
                $DoorLeafFacing,
                $DoorLeafFacingValue,
                $DoorLeafFinish,
                $DoorLeafFinishColor,
                $SheenLevel,
                $DecorativeGroves,
                $GrooveLocation,
                $GrooveWidth,
                $GrooveDepth,
                $MaxNumberOfGroove,
                $NumberOfGroove,
                $NumberOfVerticalGroove,
                $NumberOfHorizontalGroove,
                $DecorativeGrovesLeaf2,
                $GrooveLocationLeaf2,
                $IsSameAsDecorativeGroves1,
                $GrooveWidthLeaf2,
                $GrooveDepthLeaf2,
                $MaxNumberOfGrooveLeaf2,
                $NumberOfGrooveLeaf2,
                $NumberOfVerticalGrooveLeaf2,
                $NumberOfHorizontalGrooveLeaf2,
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
                $ScallopedWidth,
                $ScallopedHeight,
                $RebatedWidth,
                $RebatedHeight,
                $FrameWidth,
                $FrameHeight,
                $HeadFrameThickness ,
                $BottomFrameThickness ,
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
                $opGlassIntegrity,
                $OPGlassType,
                $OPGlassThickness,
                $opglazingSystemsvalue,
                $OPGlazingSystemsThickness,
                $OPGlazingBeads,
                $OPGlazingBeadsThickness,
                $OPGlazingBeadsHeight, // confusion
                $OPGlazingBeadsFixingDetail,
                $OPGlazingBeadSpecies,

                $SideLight1,
                $SL1GlassIntegrity,
                $SideLight1GlassType,
                $SL1GlassThickness,
                $SL1GlazingSystems,
                $SL1GlazingSystemsThickness,
                $BeadingType,
                $SideLight1FrameThickness,
                $SL1GlazingBeadsFixingDetail,
                $SL1GlazingBeadSpecies,
                $SL1Width,
                $SL1Height,
                $SlBeadThickness,
                $SlBeadHeight,
                $SL1Depth,
                $SL1Transom,
                $SL1TransomDepth,
                $SL1transomThickness,

                $SideLight2,
                $DoYouWantToCopySameAsSL1,
                $SL2GlassIntegrity,
                $SideLight2GlassType,
                $SL2GlassThickness,
                $SL2GlazingSystems,
                $SL2GlazingSystemsThickness,
                $SideLight2BeadingType,
                $SideLight2FrameThickness,
                $SL2GlazingBeadsFixingDetail,
                $SideLight2GlazingBeadSpecies,
                $SL2Width,
                $SL2Height,
                $SL2Depth,
                $SL2Transom,
                $SL2TransomDepth,
                $SL2transomThickness,
                $SLtransomHeightFromTop,

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
            '',
            $SumDoorQuantity,
            '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
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
            'Leaf Type',
            'Frame On/Off ',
            'Floor ',
            'Door Number ',
            'location ',
            'Door Quantity ',
            'Four Sided Frame ',
            'Door Type ',
            'Fire Rating ',
            'Doorset Type ',
            'Swing Type ',
            'Latch Type ',
            'Handing ',
            'Pull Towards ',
            'COC ',
            'Tollerance ',
            'Dropseal',
            'Undercut ',
            'Floor Finish ',
            'GAP ',
            'Frame Thickness ',
            'Ironmongery Set ',
            'Select Folder ',
            'Ironmongery ID ',
            'SOHeight ',
            'SOWidth ',
            'SOWallThick ',
            'LeafWidth1 ',
            'LeafWidth2 ',
            'LeafHeight ',
            'Leaf Thickness ',
            'DoorLeaf Facing ',
            'DoorLeaf Facing Value ',
            'DoorLeaf Finish ',
            'DoorLeaf Finish Color ',
            'SheenLevel ',
            'Decorative Groves ',
            'Groove Location ',
            'Groove Width ',
            'Groove Depth ',
            'Max Number Of Groove ',
            'Number Of Groove ',
            'Number Of Vertical Groove ',
            'Number Of Horizontal Groove ',
            'Decorative Groves Leaf2',
            'Groove Location Leaf2',
            'Is Same As Decorative Groves 1',
            'Groove Width Leaf2',
            'Groove Depth Leaf2',
            'Max Number Of Groove Leaf2',
            'Number Of Groove Leaf2',
            'Number Of Vertical Groove Leaf2',
            'Number Of Horizontal Groove Leaf2',
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
            'Scalloped Width ',
            'Scalloped Depth ',
            'Rebated Width ',
            'Rebated Depth  ',
            'Frame Width ',
            'Frame Height ',
            'Head Frame Thickness ',
            'Bottom Frame Thickness ',
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
            'opGlass Integrity ',
            'OPGlass Type ',
            'OPGlass Thickness',
            'OPGlass Glazing Systems',
            'OPGlass Glazing System Thickness',
            'OPGlazing Beads',
            'OPGlazing Glazing Beads Thickness',
            'OPGlazing Glazing Beads Width', // confusion
            'OPGlazing Glazing Bead Fixing Detail',
            'OPGlazing Bead Species ',
            'SideLight1 ',
            'SideLight1 Glass Integrity ',
            'SideLight1 Glass Type ',
            'Side Light 1 Glass Thickness',
            'Side Light 1 Glazing Systems',
            'Side Light 1 Glazing System Thickness',
            'Beading Type ',
            'Side Light 1 Frame Thickness',
            // 'Side Light 1 Glazing Beads Width',
            'Side Light 1 Glazing Bead Fixing Detail',
            'SL1 Glazing Bead Species ',
            'SL1Width ',
            'SL1Height ',
            'SL Bead Depth ',
            'SlBead Height ',
            'SL1 Frame Depth ',
            'SL1Transom ',
            'SL1 Transom Depth ',
            'SL1 Transom Thickness ',
            'SideLight2 ',
            'Do You Want To Copy Same As SL1 ',
            'Side Light 2 Glass Integrity ',
            'SideLight2 Glass Type ',
            'Side Light 2 Glass Thickness',
            'Side Light 2 Glazing Systems',
            'Side Light 2 Glazing System Thickness',
            'SideLight2 Beading Type ',
            'Side Light 2 Frame Thickness',
            'Side Light 2 Glazing Bead Fixing Detail',
            'SideLight2 Glazing Bead Species ',
            'SL2Width ',
            'SL2Height ',
            'SL2 Frame Depth ',
            'SL2Transom ',
            'SL2 Transom Depth ',
            'SL2 Transom Thickness ',
            'SLtransom Heigh From Top ',
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
                $cellRange = 'A1:GL1'; // All headers
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
                $event->sheet->getStyle("A1:GL1")->getAlignment()->setTextRotation(90)->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(10)->setRowHeight(60);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
