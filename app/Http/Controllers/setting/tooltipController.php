<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Tooltip;

class tooltipController extends Controller
{
    public function tooltip()
    {
        $tooltip = Tooltip::first();
        return view('Setting.tooltip',compact('tooltip'));
    }
    public function submittooltip(Request $request)
    {
        $update_val = $request->updval;
		if(!is_null($update_val)){
            $ee = Tooltip::find($update_val);
        } else {
            $ee = new Tooltip;
            $ee->created_at = date('Y-m-d H:i:s'); 
        }
        // Main Options
            $ee->leafConstruction = $request->leafConstruction;
            $ee->doorType = $request->doorType;
            $ee->DoorNo = $request->DoorNo;
            $ee->fireRating = $request->fireRating;
            $ee->doorsetType = $request->doorsetType;
            $ee->swingType = $request->swingType;
            $ee->latchType = $request->latchType;
            $ee->Handing = $request->Handing;
            $ee->OpensInwards = $request->OpensInwards;
            $ee->COC = $request->COC;
            $ee->doorQuantity = $request->doorQuantity;
            $ee->floor = $request->floor;
            $ee->location = $request->location;
            $ee->tollerance = $request->tollerance;
            $ee->undercut = $request->undercut;
            $ee->floorFinish = $request->floorFinish;
            $ee->gap = $request->gap;
            $ee->frameThickness = $request->frameThickness;
            $ee->ironmongerySet = $request->ironmongerySet;
            $ee->selectironmongerySet = $request->selectironmongerySet;
        
        // Door Dimensions & Door Leaf
            $ee->sOWidth = $request->sOWidth;
            $ee->sOHeight = $request->sOHeight;
            $ee->sODepth = $request->sODepth;
            $ee->leafWidth1 = $request->leafWidth1;
            $ee->leafWidth2 = $request->leafWidth2;
            $ee->leafHeightNoOP = $request->leafHeightNoOP;
            $ee->doorThickness = $request->doorThickness;
            $ee->doorLeafFacingValue = $request->doorLeafFacingValue;
            $ee->doorLeafFacing = $request->doorLeafFacing;
            $ee->doorLeafFinishColor = $request->doorLeafFinishColor;
            $ee->decorativeGroves = $request->decorativeGroves;
            $ee->grooveLocation = $request->grooveLocation;
            $ee->grooveWidth = $request->grooveWidth;
            $ee->grooveDepth = $request->grooveDepth;
            $ee->maxNumberOfGroove = $request->maxNumberOfGroove;
            $ee->numberOfGroove = $request->numberOfGroove;
            $ee->numberOfVerticalGroove = $request->numberOfVerticalGroove;
            $ee->numberOfHorizontalGroove = $request->numberOfHorizontalGroove;

        // Vision Panel
            $ee->leaf1VisionPanel = $request->leaf1VisionPanel;
            $ee->leaf1VisionPanelShape = $request->leaf1VisionPanelShape;
            $ee->visionPanelQuantity = $request->visionPanelQuantity;
            $ee->AreVPsEqualSizes = $request->AreVPsEqualSizes;
            $ee->distanceFromTopOfDoor = $request->distanceFromTopOfDoor;
            $ee->distanceFromTheEdgeOfDoor = $request->distanceFromTheEdgeOfDoor;
            $ee->distanceBetweenVPs = $request->distanceBetweenVPs;
            $ee->vP1Width = $request->vP1Width;
            $ee->vP1Height1 = $request->vP1Height1;
            $ee->vP1Height2 = $request->vP1Height2;
            $ee->vP1Height3 = $request->vP1Height3;
            $ee->vP1Height4 = $request->vP1Height4;
            $ee->vP1Height5 = $request->vP1Height5;
            $ee->leaf1VpAreaSizeM2 = $request->leaf1VpAreaSizeM2;
            $ee->leaf2VisionPanel = $request->leaf2VisionPanel;
            $ee->vpSameAsLeaf1 = $request->vpSameAsLeaf1;
            $ee->visionPanelQuantityforLeaf2 = $request->visionPanelQuantityforLeaf2;
            $ee->AreVPsEqualSizesForLeaf2 = $request->AreVPsEqualSizesForLeaf2;
            $ee->distanceFromTopOfDoorforLeaf2 = $request->distanceFromTopOfDoorforLeaf2;
            $ee->distanceFromTheEdgeOfDoorforLeaf2 = $request->distanceFromTheEdgeOfDoorforLeaf2;
            $ee->distanceBetweenVPsforLeaf2 = $request->distanceBetweenVPsforLeaf2;
            $ee->vP2Width = $request->vP2Width;
            $ee->vP2Height1 = $request->vP2Height1;
            $ee->vP2Height2 = $request->vP2Height2;
            $ee->vP2Height3 = $request->vP2Height3;
            $ee->vP2Height4 = $request->vP2Height4;
            $ee->vP2Height5 = $request->vP2Height5;
            $ee->lazingIntegrityOrInsulationIntegrity = $request->lazingIntegrityOrInsulationIntegrity;
            $ee->glassType = $request->glassType;
            $ee->glassThickness = $request->glassThickness;
            $ee->glazingSystems = $request->glazingSystems;
            $ee->glazingSystemsThickness = $request->glazingSystemsThickness;
            $ee->glazingBeads = $request->glazingBeads;
            $ee->glazingBeadsThickness = $request->glazingBeadsThickness;
            $ee->glazingBeadsWidth = $request->glazingBeadsWidth;
            $ee->glazingBeadsHeight = $request->glazingBeadsHeight;
            $ee->glazingBeadsFixingDetail = $request->glazingBeadsFixingDetail;
            $ee->glazingBeadSpecies = $request->glazingBeadSpecies;


        // Frame
            $ee->frameMaterial = $request->frameMaterial;
            $ee->frameType = $request->frameType;
            $ee->plantonStopWidth = $request->plantonStopWidth;
            $ee->plantonStopHeight = $request->plantonStopHeight;
            $ee->rebatedWidth = $request->rebatedWidth;
            $ee->rebatedHeight = $request->rebatedHeight;
            $ee->frameTypeDimensions = $request->frameTypeDimensions;
            $ee->frameWidth = $request->frameWidth;
            $ee->frameHeight = $request->frameHeight;
            $ee->frameDepth = $request->frameDepth;
            $ee->frameFinish = $request->frameFinish;
            $ee->framefinishColor = $request->framefinishColor;
            $ee->extLiner = $request->extLiner;
            $ee->frameCostuction = $request->frameCostuction;
            $ee->extLinerValue = $request->extLinerValue;
            $ee->extLinerSize = $request->extLinerSize;
            $ee->extLinerThickness = $request->extLinerThickness;
            $ee->extLinerFinish = $request->extLinerFinish;
            $ee->intumescentSeal = $request->intumescentSeal;
            // $ee->intumescentSealColor = $request->intumescentSealColor;
            $ee->intumescentSealSize = $request->intumescentSealSize;
            $ee->specialFeatureRefs = $request->specialFeatureRefs;

        // Over Panel Section 
            $ee->overpanel = $request->overpanel;
            $ee->OPLippingThickness = $request->OPLippingThickness;
            $ee->oPWidth = $request->oPWidth;
            $ee->oPHeigth = $request->oPHeigth;
            $ee->OpBeadThickness = $request->OpBeadThickness;
            $ee->OpBeadHeight = $request->OpBeadHeight;             
            $ee->opTransom = $request->opTransom;
            $ee->transomThickness = $request->transomThickness;
            $ee->opGlassIntegrity = $request->opGlassIntegrity;
            $ee->opGlassType = $request->opGlassType;
            $ee->opGlazingBeads = $request->opGlazingBeads;
            $ee->opGlazingBeadSpecies = $request->opGlazingBeadSpecies;

        // SIDE LIGHT
            $ee->sideLight1 = $request->sideLight1;
            $ee->sideLight1GlassType = $request->sideLight1GlassType;
            $ee->SideLight1BeadingType = $request->SideLight1BeadingType;
            $ee->SideLight1GlazingBeadSpecies = $request->SideLight1GlazingBeadSpecies;
            $ee->SL1Width = $request->SL1Width;
            $ee->SL1Height = $request->SL1Height;
            $ee->SlBeadThickness = $request->SlBeadThickness;
            $ee->SlBeadHeight = $request->SlBeadHeight;
            $ee->SL1Depth = $request->SL1Depth;
            $ee->SL1Transom = $request->SL1Transom;
            $ee->sideLight2 = $request->sideLight2;
            $ee->copyOfSideLite1 = $request->copyOfSideLite1;
            $ee->sideLight2GlassType = $request->sideLight2GlassType;
            $ee->SideLight2BeadingType = $request->SideLight2BeadingType;
            $ee->SideLight2GlazingBeadSpecies = $request->SideLight2GlazingBeadSpecies;
            $ee->SL2Width = $request->SL2Width;
            $ee->SL2Height = $request->SL2Height;
            $ee->SL2Depth = $request->SL2Depth;
            $ee->SL2Transom = $request->SL2Transom;
            $ee->SLtransomHeightFromTop = $request->SLtransomHeightFromTop;
            $ee->SLtransomThickness = $request->SLtransomThickness;

        // LIPPING AND INTUMESCENT
            $ee->lippingType = $request->lippingType;
            $ee->lippingThickness = $request->lippingThickness;
            $ee->lippingSpecies = $request->lippingSpecies;
            $ee->meetingStyle = $request->meetingStyle;
            $ee->scallopedLippingThickness = $request->scallopedLippingThickness;
            $ee->flatLippingThickness = $request->flatLippingThickness;
            $ee->rebatedLippingThickness = $request->rebatedLippingThickness;
            $ee->coreWidth1 = $request->coreWidth1;
            $ee->coreWidth2 = $request->coreWidth2;
            $ee->coreHeight = $request->coreHeight;
            $ee->intumescentSealType = $request->intumescentSealType;
            $ee->intumescentSealLocation = $request->intumescentSealLocation;
            $ee->intumescentSealColor = $request->intumescentSealColor;
            $ee->intumescentSealArrangement = $request->intumescentSealArrangement;

        // ACCOUSTICS
            $ee->accoustics = $request->accoustics;
            $ee->rWdBRating = $request->rWdBRating;
            $ee->perimeterSeal1 = $request->perimeterSeal1;
            $ee->perimeterSeal2 = $request->perimeterSeal2;
            $ee->thresholdSeal1 = $request->thresholdSeal1;
            $ee->thresholdSeal2 = $request->thresholdSeal2;
            $ee->accousticsJambs = $request->accousticsJambs;
            $ee->accousticsHead = $request->accousticsHead;
            $ee->thresholdSeal = $request->thresholdSeal;
            $ee->accousticsSeal = $request->accousticsSeal;
            $ee->accousticsmeetingStiles = $request->accousticsmeetingStiles;
            $ee->accousticsglassType = $request->accousticsglassType;


        // ARCHITRAVE
            $ee->Architrave = $request->Architrave;
            $ee->architraveMaterial = $request->architraveMaterial;
            $ee->architraveType = $request->architraveType;
            $ee->architraveWidth = $request->architraveWidth;
            $ee->architraveHeight = $request->architraveHeight;
            $ee->architraveDepth = $request->architraveDepth;
            $ee->architraveFinish = $request->architraveFinish;
            $ee->architraveFinishcolor = $request->architraveFinishcolor;
            $ee->architraveSetQty = $request->architraveSetQty;
        
        // Fitting Hardware/Ironmongery
            $ee->HingesKey = $request->HingesKey;
            $ee->FloorSpringKey = $request->FloorSpringKey;
            $ee->lockesAndLatchesKey = $request->lockesAndLatchesKey;
            $ee->flushBoltsKey = $request->flushBoltsKey;
            $ee->concealedOverheadCloserKey = $request->concealedOverheadCloserKey;
            $ee->pullHandlesKey = $request->pullHandlesKey;
            $ee->pushHandlesValue = $request->pushHandlesValue;
            $ee->kickPlates = $request->kickPlates;
            $ee->doorSelectorsKey = $request->doorSelectorsKey;
            $ee->panicHardwareKey = $request->panicHardwareKey;
            $ee->doorSecurityViewerKey = $request->doorSecurityViewerKey;
            $ee->morticeddropdownsealsKey = $request->morticeddropdownsealsKey;
            $ee->facefixeddropsealsKey = $request->facefixeddropsealsKey;
            $ee->thresholdSealKey = $request->thresholdSealKey;
            $ee->airtransfergrillsKey = $request->airtransfergrillsKey;
            $ee->letterplatesKey = $request->letterplatesKey;
            $ee->cableWays = $request->cableWays;
            $ee->safeHingeKey = $request->safeHingeKey;
            $ee->leverHandleKey = $request->leverHandleKey;
            $ee->doorSignageKey = $request->doorSignageKey;
            $ee->faceFixedDoorClosersKey = $request->faceFixedDoorClosersKey;
            $ee->thumbturnKey = $request->thumbturnKey;
            $ee->keyholeEscutcheonKey = $request->keyholeEscutcheonKey;

        // Transport
            $ee->VehicleType = $request->VehicleType;
            $ee->deliveryTime = $request->deliveryTime;
            $ee->packaging = $request->packaging;

        $ee->updated_at = date('Y-m-d H:i:s');
        $ee->save();

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Tooltip update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'Tooltip added successfully!');	
        }
    }
}