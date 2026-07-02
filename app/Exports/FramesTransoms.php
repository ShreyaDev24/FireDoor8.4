<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Models\Item;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\DoorFrameConstruction;
use App\Models\BOMCalculation;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FramesTransoms implements FromCollection, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $id,$vid,$result,$section;

    function __construct($id,$vid,$result,$section = null) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
        $this->section = $section;
    }

    public function collection()
    {
        $quotation = Quotation::select('project.*', 'quotation.*', 'customers.CstCompanyName', 'project.ProjectName as projectname')
            ->leftJoin('project', 'quotation.ProjectId', '=', 'project.id')
            ->leftJoin('customers', 'customers.UserId', 'quotation.MainContractorId')
            ->where('quotation.id', $this->id)->first();

        $item = Item::join('item_master', 'items.itemId', 'item_master.itemID')
            ->leftJoin('door_frame_construction', 'items.DoorFrameConstruction', 'door_frame_construction.DoorFrameConstruction')
            ->leftJoin('lipping_species', 'lipping_species.id', 'items.FrameMaterial')
            ->where('QuotationId', $this->id)
            ->where('VersionId', $this->vid)
            ->where('door_frame_construction.UserId', Auth::user()->id)
            ->select('item_master.*', 'items.*', 'lipping_species.SpeciesName', 'door_frame_construction.Width', 'door_frame_construction.Height')
            ->orderBy('items.itemId', 'ASC')
            ->get();

        $ids = Auth::user()->UserType == 3
            ? User::where('UserType', 3)->where('id', Auth::user()->id)->first()->CreatedBy
            : Auth::user()->id;

        $halflapedjoint = DoorFrameConstruction::where('UserId', $ids)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();
        $mitre_joint = DoorFrameConstruction::where('UserId', $ids)->where('DoorFrameConstruction', 'Mitre_Joint')->first();
        $mortice_tenon_joint = DoorFrameConstruction::where('UserId', $ids)->where('DoorFrameConstruction', 'Mortice_&_Tenon_Joint')->first();
        $butt_joint = DoorFrameConstruction::where('UserId', $ids)->where('DoorFrameConstruction', 'Butt_Joint')->first();
        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        // Client requirement for Leg x2 in all cut lists:
        //   LEG = O/A Frame Height - (Frame Thickness - Rebate) - MS
        // - Frame Thickness is deducted ONCE. For a Rebated Frame the effective
        //   thickness is (Frame Thickness - Rebate), e.g. 44 - 12 = 32.
        // - MS is a further deduction taken by its MAGNITUDE, so a stored -5 still
        //   means "take another 5 off".
        // Example: 2090 - (44 - 12) - 5 = 2090 - 32 - 5 = 2053
        // NOTE: $thicknessCount is intentionally NOT applied - the client wants the
        //   thickness off once on every row (including four-sided / overpanel /
        //   side-light), so the extra head/bottom rail is not deducted from the leg.
        $calculateLeg = function ($frameHeight, $frameThickness, $ms = 0, $frameType = null, $rebate = 0, $thicknessCount = 1) {
            $effectiveFrameThickness = floatval($frameThickness ?? 0);

            if ($frameType == 'Rebated_Frame') {
                $effectiveFrameThickness = $effectiveFrameThickness - floatval($rebate ?? 0);
            }

            return floatval($frameHeight ?? 0)
                - $effectiveFrameThickness
                - abs(floatval($ms ?? 0));
        };

        $k = 1;
        $data = [];

        // NOTE: the first row must have a non-empty cell A1, otherwise maatwebsite's
        // chunked writer (1000 rows/chunk) sees cellExists('A1') === false via hasRows()
        // and every chunk after the first overwrites from A1 - corrupting/truncating
        // any sheet with more than 1000 rows. So the title goes on row 1 (no blank row).
        $data[] = array_merge(['Frames and Transoms'], array_fill(0, 31, ''));

        $data[] = [ // Row 2 - Actual column headings
            'Door Number',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Door Type',
            'Ironmongery Ref',
            'Fire Rating',
            'Door Thickness',
            'Frame Material',
            'O/A Frame H',
            'O/A Frame W',
            'Frame Thickness',
            'Plant on stop thickness',
            'Plant on stop Width',
            'Rebate Width',
            'Rebate Depth',
            'Scalloped Width',
            'Scalloped Depth',
            'Frame Depth',
            'Leg x2',
            'Head',
            'Stop Leg x 2',
            'Stop Head',
            'Stop Bottom',
            'Bottom- 4 Sided Frame',
            'Handing',
            'Finish',
            'Undercut',
            'Transom',
            'Mullion',
            'DB Rating',
            'Notes'
        ];

        foreach ($item as $value) {
             $ms = abs(floatval($Height ?? 0));

            $leg = $calculateLeg(
                $value->FrameHeight,
                $value->FrameThickness,
                $ms,
                $value->FrameType,
                $value->RebatedHeight
            );
            $head = $value->FrameWidth + $value->Width;
            $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
            $cutSizeH = 0;
            if($quotation->configurableitems == '1' || $quotation->configurableitems == '2' || $quotation->configurableitems == '7' || $quotation->configurableitems == '8'){
                $cutSizeH = ($value->LeafHeight  - $value->LippingThickness - $value->LippingThickness);
            }else{
                $AdjustmentLeafHeightNoOP = $value->AdjustmentLeafHeightNoOP ?? 0;
                if($AdjustmentLeafHeightNoOP == 0){
                    $cutSizeH = $value->LeafHeight;
                }else{
                    $cutSizeH = (floatval($value->LeafHeight ?? 0) + floatval($AdjustmentLeafHeightNoOP ?? 0)) - floatval($AdjustmentLeafHeightNoOP ?? 0) - floatval($value->LippingThickness ?? 0);
                }
            }

            $FrameType = '';
            if ($value->FrameType == 'Plant_on_Stop') {
                $FrameType = $value->PlantonStopHeight;
            } elseif ($value->FrameType == 'Rebated_Frame') {
                $FrameType = $value->RebatedHeight;
            }
            $stopleg2 = $leg - floatval($FrameType) - 0;

            $Height = 0;
            $Width = 0;
            if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                $Height = $halflapedjoint->Height ?? 0;
                $Width = $halflapedjoint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                $Height = $mitre_joint->Height ?? 0;
                $Width = $mitre_joint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                $Height = $mortice_tenon_joint->Height ?? 0;
                $Width = $mortice_tenon_joint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                $Height = $butt_joint->Height ?? 0;
                $Width = $butt_joint->Width ?? 0;
            }

            $leg = $calculateLeg(
                    $value->FrameHeight,
                    $value->FrameThickness,
                    $Height,
                    $value->FrameType,
                    $value->RebatedHeight
                );
            $head = $value->FrameWidth + $Width;
            $stopleg2 = $calculateLeg($value->FrameHeight, $value->FrameThickness, 0, $value->FrameType, $value->RebatedHeight);
            $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;

            if($value->FrameType == 'Plant_on_Stop'){
                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if(!empty($allSettings['PlantOn.HalfLipped'])){
                        $stophead += $allSettings['PlantOn.HalfLipped']->Width;
                        $stopleg2 += $allSettings['PlantOn.HalfLipped']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                    if(!empty($allSettings['PlantOn.Mitre'])){
                        $stophead += $allSettings['PlantOn.Mitre']->Width;
                        $stopleg2 += $allSettings['PlantOn.Mitre']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                    if(!empty($allSettings['PlantOn.Mortice1'])){
                        $stophead += $allSettings['PlantOn.Mortice1']->Width;
                        $stopleg2 += $allSettings['PlantOn.Mortice1']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                    if(!empty($allSettings['PlantOn.Butt'])){
                        $stophead += $allSettings['PlantOn.Butt']->Width;
                        $stopleg2 += $allSettings['PlantOn.Butt']->Height;
                    }
                }
            }else{
                $stophead += $Width;
                $stopleg2 += $Height;
            }

            $foursidedFrame = 0;
            $stopbottom = 0;
            if($value->FourSidedFrame == 1){
                $foursidedFrame = $head;
                $stopbottom = $stophead;
                 $leg = $calculateLeg(
                        $value->FrameHeight,
                        $value->FrameThickness,
                        $Height,
                        $value->FrameType,
                        $value->RebatedHeight,
                        2
                    );
            }

            $data[] = [
                $value->doorNumber,
                $value->plot_ref_no,
                $value->certification_no,
                $value->DoorType,
                IronmongerySetName($value->IronmongeryID),
                $value->FireRating,
                $value->LeafThickness,
                $value->SpeciesName,
                $value->FrameHeight,
                $value->FrameWidth,
                $value->FrameThickness,
                $value->PlantonStopHeight,
                $value->PlantonStopWidth,
                $value->RebatedWidth,
                $value->RebatedHeight,
                $value->ScallopedWidth,
                $value->ScallopedHeight,
                $value->FrameDepth,
                $leg,
                $head,
                // $stopleg2,
                // $stophead,
                '', // Empty column
                '', // Empty column
                $stopbottom, // Empty column
                $foursidedFrame, // Empty column
                $value->Handing,
                str_replace('_', ' ', $value->FrameFinish),
                $value->Undercut,
                '', // Empty column
                '', // Empty column
                $value->rWdBRating ? $value->rWdBRating : '',
                '', // Empty column
            ];



            if($value->Overpanel == 'Fan_Light' || $value->Overpanel == 'Overpanel'){

                $Height = 0;
                $Width = 0;


                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if(!empty($allSettings['Fanlight.HalfLipped'])){
                        $Height = $allSettings['Fanlight.HalfLipped']->Height;
                        $Width = $allSettings['Fanlight.HalfLipped']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                    if(!empty($allSettings['Fanlight.Mitre'])){
                        $Height = $allSettings['Fanlight.Mitre']->Height;
                        $Width = $allSettings['Fanlight.Mitre']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                    if(!empty($allSettings['Fanlight.Mortice1'])){
                        $Height = $allSettings['Fanlight.Mortice1']->Height;
                        $Width = $allSettings['Fanlight.Mortice1']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                    if(!empty($allSettings['Fanlight.Butt'])){
                        $Height = $allSettings['Fanlight.Butt']->Height;
                        $Width = $allSettings['Fanlight.Butt']->Width;
                    }
                }


                $leg = $calculateLeg($value->OPHeigth, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                $head = $value->OPWidth + $Width;

               $foursidedFrame = 0;
                $stopbottom = 0;
                if($value->FourSidedFrame == 1){
                    $foursidedFrame = $head;
                    $stopbottom = $stophead;
                    $leg = $calculateLeg($value->OPHeigth, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                }

                $SLWidth = 0;

                if (!empty($value->SideLight1) && $value->SideLight1 === 'Yes') {
                    $SLWidth += (float) $value->SL1Width;
                }

                if (!empty($value->SideLight2) && $value->SideLight2 === 'Yes') {
                    $SLWidth += (float) $value->SL2Width;
                }


                $data[] = [
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->DoorType . ' '. $value->Overpanel,
                    IronmongerySetName($value->IronmongeryID),
                    $value->FireRating,
                    $value->LeafThickness,
                    $value->SpeciesName,
                    $value->OPHeigth,
                    $value->FrameWidth + $SLWidth,
                    $value->FrameThickness,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->FrameDepth,
                    $leg,
                    $head,
                    '',
                    '',
                    '', // Empty column
                    $foursidedFrame, // Empty column
                    '',
                    str_replace('_', ' ', $value->FrameFinish),
                    '',
                    '', // Empty column
                    '', // Empty column
                    $value->rWdBRating ? $value->rWdBRating : '',
                    '', // Empty column
                ];
            }
            if($value->SideLight1 == 'Yes'){

                $Height = 0;
                $Width = 0;


                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if(!empty($allSettings['SideLight.HalfLipped'])){
                        $Height = $allSettings['SideLight.HalfLipped']->Height;
                        $Width = $allSettings['SideLight.HalfLipped']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                    if(!empty($allSettings['SideLight.Mitre'])){
                        $Height = $allSettings['SideLight.Mitre']->Height;
                        $Width = $allSettings['SideLight.Mitre']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                    if(!empty($allSettings['SideLight.Mortice1'])){
                        $Height = $allSettings['SideLight.Mortice1']->Height;
                        $Width = $allSettings['SideLight.Mortice1']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                    if(!empty($allSettings['SideLight.Butt'])){
                        $Height = $allSettings['SideLight.Butt']->Height;
                        $Width = $allSettings['SideLight.Butt']->Width;
                    }
                }


                $leg = $calculateLeg($value->SL1Height, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                $head = $value->SL1Width + $Width;

                $foursidedFrame = 0;
                $stopbottom = 0;
                if($value->FourSidedFrame == 1){
                    $foursidedFrame = $head;
                    $stopbottom = $stophead;
                    $leg = $calculateLeg($value->SL1Height, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                }

                $data[] = [
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->DoorType . ' Side Light 1',
                    IronmongerySetName($value->IronmongeryID),
                    $value->FireRating,
                    $value->LeafThickness,
                    $value->SpeciesName,
                    $value->SL1Height,
                    $value->SL1Width,
                    $value->FrameThickness,
                      '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->SL1Depth,
                    $leg,
                    $head,
                    '',
                    '',
                    '', // Empty column
                    $foursidedFrame, // Empty column
                    '',
                    str_replace('_', ' ', $value->FrameFinish),
                    '',
                    '', // Empty column
                    '', // Empty column
                    $value->rWdBRating ? $value->rWdBRating : '',
                    '', // Empty column
                ];
            }
            if($value->SideLight2 == 'Yes'){

                 $Height = 0;
                $Width = 0;


                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if(!empty($allSettings['SideLight.HalfLipped'])){
                        $Height = $allSettings['SideLight.HalfLipped']->Height;
                        $Width = $allSettings['SideLight.HalfLipped']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                    if(!empty($allSettings['SideLight.Mitre'])){
                        $Height = $allSettings['SideLight.Mitre']->Height;
                        $Width = $allSettings['SideLight.Mitre']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                    if(!empty($allSettings['SideLight.Mortice1'])){
                        $Height = $allSettings['SideLight.Mortice1']->Height;
                        $Width = $allSettings['SideLight.Mortice1']->Width;
                    }
                }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                    if(!empty($allSettings['SideLight.Butt'])){
                        $Height = $allSettings['SideLight.Butt']->Height;
                        $Width = $allSettings['SideLight.Butt']->Width;
                    }
                }


                $leg = $calculateLeg($value->SL2Height, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                $head = $value->SL2Width + $Width;

                $foursidedFrame = 0;
                $stopbottom = 0;
                if($value->FourSidedFrame == 1){
                    $foursidedFrame = $head;
                    $stopbottom = $stophead;
                    $leg = $calculateLeg($value->SL2Height, $value->FrameThickness, $Height, $value->FrameType, $value->RebatedHeight, 2);
                }

                $data[] = [
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->DoorType . ' Side Light 2',
                    IronmongerySetName($value->IronmongeryID),
                    $value->FireRating,
                    $value->LeafThickness,
                    $value->SpeciesName,
                    $value->SL2Height,
                    $value->SL2Width,
                    $value->FrameThickness,
                      '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->SL2Depth,
                    $leg,
                    $head,
                    '',
                    '',
                    '', // Empty column
                    $foursidedFrame, // Empty column
                    '',
                    str_replace('_', ' ', $value->FrameFinish),
                    '',
                    '', // Empty column
                    '', // Empty column
                    $value->rWdBRating ? $value->rWdBRating : '',
                    '', // Empty column
                ];
            }
        }

        // Blank row
        $data[] = array_fill(0, 32, '');
        $data[] = array_fill(0, 32, '');
        $data[] = array_fill(0, 32, '');


        // SCREEN INFO section - only render its heading + column header when
        // there are actually side screens, otherwise it shows as a stray
        // heading floating between the frame list and the Summary.
        if (!empty($this->result) && count($this->result) > 0) {
            // SCREEN INFO header row (merged A:U later)
            $data[] = array_merge(['SCREEN INFO'], array_fill(0, 11, ''));

            // Screen info column headers (21 labels -> pad to 32 columns)
            $data[] = array_merge([
                'S.No',
                'Screen No',
                'Plot Number/Ref',
                'IFC/Certifire No/Q mark Plug',
                'Screen Type',
                'Frame Material/Finish',
                'Frame Finish',
                'Qty Per Screen Type',
                'Quantity of screen types',
                'Screen Dims ',
                'O/A Frame Height',
                'O/A Frame Width',
                'Frame Thickness',
                'Frame Depth',
                'Leg x 2',
                'Head',
                'Transom',
                'Transom QTY',
                'Mullion',
                'Mullion QTY',
                'Notes',
            ], array_fill(0, 32 - 21, ''));
        }
         $j = 1;
        foreach($this->result as $value){
            $screenNumber = $value->screenNumber;
            $ScreenType = $value->ScreenType;
            $FrameHeight = $value->FrameHeight;
            $FrameWidth = $value->FrameWidth;
            $FrameThickness = $value->FrameThickness;
            $FrameDepth = $value->FrameDepth;
            $poNmber = $value->plot_ref_no;
            $Certificate = $value->certification_no;
            $FrameMF = lippingName($value->FrameMaterial);
            $Finish = $value->Finish;
            $FrameDimensions = [
                'Head' => $FrameHeight . ' x ' .$value->FrameWidth . ' x ' . $value->FrameThickness,
                'Bottom' => $value->FrameWidth . ' x ' . $value->FrameDepth . ' x ' . $value->FrameThickness,
                'Sides' => $value->FrameHeight . ' x ' . $value->FrameDepth . ' x ' . $value->FrameThickness,
            ];
            $Quantities = [
                'Head' => 1,
                'Bottom' => 1,
                'Sides' => 2,
            ];

            $Height = $Width = $TransomWidth = $MullionWidth = 0;
            if(!empty($allSettings['ScreenConstruction.FrameHead'])){
                $Height = $allSettings['ScreenConstruction.FrameHead']->Height;
                $Width = $allSettings['ScreenConstruction.FrameHead']->Width;
            }
            if(!empty($allSettings['ScreenConstruction.Transom'])){
                $TransomWidth = $allSettings['ScreenConstruction.Transom']->Width;
            }
            if(!empty($allSettings['ScreenConstruction.Mullion'])){
                $MullionWidth = $allSettings['ScreenConstruction.Mullion']->Width;
            }

            foreach (['Head'] as $FrameLocation) {
                $Qty = $Quantities[$FrameLocation];
                $screenDim = $FrameDimensions[$FrameLocation];
                $data[] = [
                    $j++,
                    $screenNumber,
                    $poNmber,
                    $Certificate,
                    $ScreenType,
                    $FrameMF,
                    $Finish,
                    2, // QtyScreenType is constant as 1
                    $Qty,
                    $screenDim,
                    $FrameHeight,
                    $FrameWidth,
                    $FrameThickness,
                    $FrameDepth,
                    $calculateLeg($FrameHeight, $FrameThickness, $Height, null, 0, 2),
                    $FrameWidth + $Width,
                    (!empty($value->TransomQuantity) && ($value->TransomQuantity != 0))?($FrameWidth - ($FrameThickness * 2) + $TransomWidth) : 0,
                    (!empty($value->TransomQuantity) && ($value->TransomQuantity != 0))?($value->TransomQuantity) : 0,
                    (!empty($value->MullionQuantity) && ($value->MullionQuantity != 0)) ? ($FrameHeight - ($FrameThickness * 2) + $MullionWidth) : 0,
                    (!empty($value->MullionQuantity) && ($value->MullionQuantity != 0)) ? ($value->MullionQuantity) : 0,
                    '',
                ];
            }

        }


        // Screen info data
        // $data[] = array_merge([
        //     'SC01-33', '1', 'SCREEN TYPE & DOOR REF 1000X1000X1000',
        //     '0-0/30-0, 30-30, 60-0, 60-60', 'FD30s', '44', 'Redwood, EU',
        //     '1000', '1000', '44', '12', '44', '', '', '', '', '100', ''
        // ], array_fill(0, 32 - 18, ''));

            $summaryData = collect($item)
            ->groupBy(function ($row) {
                return implode('|', [
                    $row->SpeciesName,
                    $row->FireRating,
                    $row->Handing,
                    $row->FrameHeight,
                    $row->FrameWidth,
                    $row->FrameDepth
                ]);
            })
            ->map(function ($group) {
                $first = $group->first();
                return [
                    $first->SpeciesName ?? '',
                    $first->FireRating ?? '',
                    $first->Handing ?? '',
                    $first->FrameHeight ?? '',
                    $first->FrameWidth ?? '',
                    $first->FrameDepth ?? '',
                    $group->count(), // ✅ Add quantity count per group
                ];
            })
            ->values()
            ->toArray();

        // Header row for summary
        $summaryHeader = [
            'Frame Material',
            'Fire Rating',
            'Handling',
            'O/A Frame Height',
            'O/A Frame Width',
            'Frame Depth',
            'QTY', // ✅ Add qty header
        ];

        if($this->section != 'Summary'){
            // Add empty rows before summary
            $data[] = array_fill(0, 32, '');
            $data[] = array_fill(0, 32, '');
            $data[] = ['Summary', '', '', '', '', '', ''];
            $data[] = $summaryHeader;

            // Add summary rows
            $totalQty = 0;
            foreach ($summaryData as $row) {
                $totalQty += $row[6]; // sum qty
                $data[] = array_merge($row, array_fill(0, 32 - count($row), ''));
            }
        }else{
            // Add empty rows before summary
            $data = [];
            $data[] = ['Summary', '', '', '', '', '', ''];
            $data[] = $summaryHeader;

            // Add summary rows
            $totalQty = 0;
            foreach ($summaryData as $row) {
                $totalQty += $row[6]; // sum qty
                $data[] = array_merge($row, array_fill(0, 32 - count($row), ''));
            }
        }



        // ----------------------------------------------------------
        return collect($data);

    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet;

                // ----------------------------------------------------------
                // Auto-size all columns A to AC
                // ----------------------------------------------------------
                $col = 'A';
                while ($col !== 'AE') { // Adjust as per your last column
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $col++;
                }

                $highestRow = $sheet->getHighestRow();

                // ----------------------------------------------------------
                // Define main reusable styles
                // ----------------------------------------------------------

                // Green top & bottom border titles (main section headings)
                $mainTitleStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '008000'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => '008000'],
                        ],
                    ],
                ];

                // Red thick underline for column headers
                $headerRowStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],
                ];

                // Gray summary header styling
                $summaryHeaderStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9D9D9'], // light gray background
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                // ----------------------------------------------------------
                // Loop through rows to apply conditional styling
                // ----------------------------------------------------------
                for ($i = 1; $i <= $highestRow; $i++) {
                    $val = trim((string) $sheet->getCell("A{$i}")->getValue());

                    // ✅ Green title (merged and bordered top/bottom)
                    if (in_array($val, ['Door Order Sheet', 'Frames and Transoms'])) {
                        $sheet->mergeCells("A{$i}:AE{$i}");
                        $sheet->getStyle("A{$i}:AE{$i}")->applyFromArray($mainTitleStyle);
                    }

                    // ✅ Green title for "SCREEN INFO"
                    if ($val === 'SCREEN INFO') {
                        $sheet->mergeCells("A{$i}:U{$i}");
                        $sheet->getStyle("A{$i}:U{$i}")->applyFromArray($mainTitleStyle);
                    }

                    // ✅ Red underline for Door Section header
                    if ($val === 'Door Number') {
                        $sheet->getStyle("A{$i}:AE{$i}")->applyFromArray($headerRowStyle);
                    }

                    // ✅ Red underline for Screen Info section header
                    if ($val === 'S.No') {
                        $lastColIndex = 1;
                        for ($colIndex = 1; $colIndex <= 100; $colIndex++) {
                            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                            $cellVal = trim((string) $sheet->getCell("{$colLetter}{$i}")->getValue());
                            if (!empty($cellVal)) {
                                $lastColIndex = $colIndex;
                            }
                        }

                        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex);
                        $sheet->getStyle("A{$i}:{$lastColLetter}{$i}")->applyFromArray($headerRowStyle);
                    }

                    // ✅ Gray background + bold for Summary table header
                    if ($val === 'Frame Material') {
                        $sheet->getStyle("A{$i}:G{$i}")->applyFromArray($summaryHeaderStyle);
                    }

                    // ✅ Optional: green merge bar for the "Summary" label row
                    if ($val === 'Summary') {
                        $sheet->mergeCells("A{$i}:G{$i}");
                        $sheet->getStyle("A{$i}:G{$i}")->applyFromArray($mainTitleStyle);
                    }
                }
            },
        ];
    }



    public function title(): string
    {
        return 'Frames and Transoms';
    }

}
