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
use App\Models\User;
use App\Models\Quotation;
use App\Models\DoorFrameConstruction;
use App\Models\BOMCalculation;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class GlassOrderSheet implements FromCollection,WithHeadings,WithEvents,WithTitle
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
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        $k = 1;
        $data = [];
        foreach($item as $value){
            if ($value->GlassType != '' && $value->GlassThickness != '' && (($value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0) || ($value->Leaf2VPHeight1 != '' && $value->Leaf2VPHeight1 != 0  && $value->Leaf2VPWidth != '' && $value->Leaf2VPWidth != 0) )){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                if(!empty($allSettings['VisionPanel.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['VisionPanel.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['VisionPanel.NRF']->Height;
                }
                if(!empty($allSettings['VisionPanel.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['VisionPanel.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['VisionPanel.FD60']->Height;
                }

                if($value->DoorsetType == 'SD'){
                    if($value->AreVPsEqualSizesForLeaf1 == 'Yes'){
                        $qty = $value->VisionPanelQuantity;
                    }else{
                        $qty = 1;
                    }
                }else{
                    if($value->AreVPsEqualSizesForLeaf2 == 'Yes'){
                        $qty = $value->Leaf2VisionPanelQuantity + $value->VisionPanelQuantity;
                    }else{
                        if(empty($value->VisionPanelQuantity)){
                            $qty = 1;
                        }else{
                            $qty = (($value->Leaf1VPHeight1)?1:0) + (($value->Leaf2VPHeight1)?1:0);
                        }
                    }
                }

                $data[] = array(
                    $value->DoorType,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->GlassThickness,
                    str_replace('_', ' ', $value->GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight1)?$value->Leaf1VPHeight1:$value->Leaf2VPHeight1) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight1)?$value->Leaf1VPHeight1:$value->Leaf2VPHeight1) + $VisionPanelHeightNFR,

                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ((($value->Leaf1VPWidth)?$value->Leaf1VPWidth:$value->Leaf2VPWidth) + $VisionPanelWidthNFR) : ((($value->Leaf1VPWidth)?$value->Leaf1VPWidth:$value->Leaf2VPWidth) + $VisionPanelWidthFD60),

                    $qty,

                    ($value->Leaf1VPHeight2 || $value->Leaf2VPHeight2) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight2)?$value->Leaf1VPHeight2:$value->Leaf2VPHeight2) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight2)?$value->Leaf1VPHeight2:$value->Leaf2VPHeight2) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight2 || $value->Leaf2VPHeight2) ? (($value->Leaf1VPHeight2)?1:0) + (($value->Leaf2VPHeight2)?1:0) : '',

                    ($value->Leaf1VPHeight3 || $value->Leaf2VPHeight3) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight3)?$value->Leaf1VPHeight3:$value->Leaf2VPHeight3) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight3)?$value->Leaf1VPHeight3:$value->Leaf2VPHeight3) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight3 || $value->Leaf2VPHeight3) ? (($value->Leaf1VPHeight3)?1:0) + (($value->Leaf2VPHeight3)?1:0) : '',

                    ($value->Leaf1VPHeight4 || $value->Leaf2VPHeight4) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight4)?$value->Leaf1VPHeight4:$value->Leaf2VPHeight4) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight4)?$value->Leaf1VPHeight4:$value->Leaf2VPHeight4) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight4 || $value->Leaf2VPHeight4) ? (($value->Leaf1VPHeight4)?1:0) + (($value->Leaf2VPHeight4)?1:0) : '',

                    ($value->Leaf1VPHeight5 || $value->Leaf2VPHeight5) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight5)?$value->Leaf1VPHeight5:$value->Leaf2VPHeight5) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight5)?$value->Leaf1VPHeight5:$value->Leaf2VPHeight5) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight5 || $value->Leaf2VPHeight5) ? (($value->Leaf1VPHeight5)?1:0) + (($value->Leaf2VPHeight5)?1:0) : '',
                    $value->rWdBRating ? $value->rWdBRating : '',
                );



                $k++;
            }

            if($value->Overpanel == 'Fan_Light'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                if(!empty($allSettings['FanlightSize.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['FanlightSize.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['FanlightSize.NRF']->Height;
                }
                if(!empty($allSettings['FanlightSize.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['FanlightSize.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['FanlightSize.FD60']->Height;
                }

                $data[] = array(
                    $value->DoorType. ' ' .$value->Overpanel,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->OPGlassThickness,

                    str_replace('_', ' ', $value->OPGlassType),

                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? ($value->OPHeigth  - ($value->OpBeadThickness * 2)) + $VisionPanelHeightFD60 : ($value->OPHeigth  - ($value->OpBeadThickness * 2)) + $VisionPanelHeightNFR,

                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? (($value->OPWidth  - ($value->OpBeadThickness * 2))  + $VisionPanelWidthNFR) : (($value->OPWidth  - ($value->OpBeadThickness * 2)) + $VisionPanelWidthFD60),

                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->rWdBRating ? $value->rWdBRating : '',
                );
            }

            if($value->SideLight1 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                $SLWidthNFR = $SLWidthFD60 = 0;
                if(!empty($allSettings['SideLightFD.FD30'])){
                    $VisionPanelWidthNFR = $allSettings['SideLightFD.FD30']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideLightFD.FD30']->Height;
                    $SLWidthNFR = $value->SL1Width - ($value->SideLight1FrameThickness * 2) + $VisionPanelWidthNFR;
                }
                if(!empty($allSettings['SideLightFD.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideLightFD.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideLightFD.FD60']->Height;
                    $SLWidthFD60 = $value->SL1Width - ($value->SideLight1FrameThickness * 2) + $VisionPanelWidthFD60;
                }



                $data[] = array(
                    $value->DoorType. ' Side Light 1',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SideLight1GlassThickness,

                    str_replace('_', ' ', $value->SideLight1GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ?
                    ($value->SL1Height  - ($value->SideLight1FrameThickness * 2)) + $VisionPanelHeightFD60 :
                    ($value->SL1Height  - ($value->SideLight1FrameThickness * 2)) + $VisionPanelHeightNFR,
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $SLWidthFD60 : $SLWidthNFR,
                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->rWdBRating ? $value->rWdBRating : '',
                );
            }

            if($value->SideLight2 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                $SLWidth = 0;
                if(!empty($allSettings['SideLightFD.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['SideLightFD.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideLightFD.NRF']->Height;
                    $SLWidth = $value->SL2Width - ($value->SideLight2FrameThickness * 2) + $VisionPanelWidthNFR;
                }
                if(!empty($allSettings['SideLightFD.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideLightFD.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideLightFD.FD60']->Height;
                    $SLWidth = $value->SL2Width - ($value->SideLight2FrameThickness * 2) + $VisionPanelWidthFD60;
                }

                $data[] = array(
                    $value->DoorType. ' Side Light 2',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SideLight2GlassThickness,

                    str_replace('_', ' ', $value->SideLight2GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ?
                    ($value->SL2Height  - ($value->SideLight2FrameThickness * 2)) + $VisionPanelHeightFD60 :
                    ($value->SL2Height  - ($value->SideLight2FrameThickness * 2)) + $VisionPanelHeightNFR,

                    $SLWidth,

                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $value->rWdBRating ? $value->rWdBRating : '',
                );
            }
        }

        // ✅ COMPLETE SUMMARY (Glass Type + all VP columns + Grand Total Qty)
        $summary = [];

        foreach ($data as $row) {
            if (empty($row[5])) continue; // skip if no Glass Type
            $glassType = trim($row[5]);

            // VP column indexes
            $vpIndexes = [
                'VP1' => ['H' => 6,  'W' => 7,  'Q' => 8],
                'VP2' => ['H' => 9,  'W' => 7, 'Q' => 10],
                'VP3' => ['H' => 11, 'W' => 7, 'Q' => 12],
                'VP4' => ['H' => 13, 'W' => 7, 'Q' => 14],
                'VP5' => ['H' => 15, 'W' => 7, 'Q' => 16],
            ];

            // initialize
            if (!isset($summary[$glassType])) {
                $summary[$glassType] = [
                    'VP1' => ['H' => 'N/A', 'W' => 'N/A', 'Q' => 0],
                    'VP2' => ['H' => 'N/A', 'W' => 'N/A', 'Q' => 0],
                    'VP3' => ['H' => 'N/A', 'W' => 'N/A', 'Q' => 0],
                    'VP4' => ['H' => 'N/A', 'W' => 'N/A', 'Q' => 0],
                    'VP5' => ['H' => 'N/A', 'W' => 'N/A', 'Q' => 0],
                ];
            }

            // fill data
            // foreach ($vpIndexes as $vp => $ix) {
            //     $height = $row[$ix['H']] ?? 'N/A';
            //     $width  = $row[$ix['W']] ?? 'N/A';
            //     $qty    = (isset($row[$ix['Q']]) && is_numeric($row[$ix['Q']])) ? (float)$row[$ix['Q']] : 0;

            //     if ($height != '' && $height != 'N/A') $summary[$glassType][$vp]['H'] = $height;
            //     if ($width  != '' && $width  != 'N/A') $summary[$glassType][$vp]['W'] = $width;
            //     $summary[$glassType][$vp]['Q'] += $qty;
            // }
            // fill data
            foreach ($vpIndexes as $vp => $ix) {
                $height = $row[$ix['H']] ?? '';
                $width  = $row[$ix['W']] ?? '';
                $qty    = (isset($row[$ix['Q']]) && is_numeric($row[$ix['Q']])) ? (float)$row[$ix['Q']] : 0;

                if ($height != '' && $height != 'N/A') {
                    $summary[$glassType][$vp]['H'] = $height;

                    if ($width != '' && $width != 'N/A') {
                        $summary[$glassType][$vp]['W'] = $width;
                    }

                    $summary[$glassType][$vp]['Q'] += $qty;
                }
            }
        }

        if($this->section != 'Summary'){
            // HEADER + SPACING
            $data[] = array_fill(0, 22, '');
            $data[] = ['Summary', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = [
                'Glass Type',
                'AP 1 Height', 'AP 1 Width', 'Qty',
                'AP 2 Height', 'AP 2 Width', 'Qty',
                'AP 3 Height', 'AP 3 Width', 'Qty',
                'AP 4 Height', 'AP 4 Width', 'Qty',
                'AP 5 Height', 'AP 5 Width', 'Qty',
                'Grand Total Qty' // ✅ NEW COLUMN
            ];
        }else{
            // HEADER + SPACING
            $data = [];
            $data[] = ['Summary', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
            $data[] = [
                'Glass Type',
                'AP 1 Height', 'AP 1 Width', 'Qty',
                'AP 2 Height', 'AP 2 Width', 'Qty',
                'AP 3 Height', 'AP 3 Width', 'Qty',
                'AP 4 Height', 'AP 4 Width', 'Qty',
                'AP 5 Height', 'AP 5 Width', 'Qty',
                'Grand Total Qty' // ✅ NEW COLUMN
            ];
        }


        // Print summary
        foreach ($summary as $type => $vpData) {
            // calculate grand total qty for all VPs
            $grandTotal = array_sum(array_column($vpData, 'Q'));

            $data[] = [
                $type,
                $vpData['VP1']['H'], $vpData['VP1']['W'], $vpData['VP1']['Q'] ?: 'N/A',
                $vpData['VP2']['H'], $vpData['VP2']['W'], $vpData['VP2']['Q'] ?: 'N/A',
                $vpData['VP3']['H'], $vpData['VP3']['W'], $vpData['VP3']['Q'] ?: 'N/A',
                $vpData['VP4']['H'], $vpData['VP4']['W'], $vpData['VP4']['Q'] ?: 'N/A',
                $vpData['VP5']['H'], $vpData['VP5']['W'], $vpData['VP5']['Q'] ?: 'N/A',
                $grandTotal // ✅ FINAL TOTAL
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        $a = [];
        if($this->section != 'Summary'){
            $a = [
                'Door Type',
                'Door Number',
                'Plot Number/Ref',
                'IFC/Certifire No/Q mark Plug',
                'Glass Thickness in mm',
                'Glass Type',
                'AP1 H',
                'AP W',
                'QTY',
                'AP2 H',
                'QTY',
                'AP3 H',
                'QTY',
                'AP4 H',
                'QTY',
                'AP5 H',
                'QTY',
                'DB Rating'
            ];
        }

        $b = ['Glass Order Sheet'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {

            // --------------------------------------------
            // 🔹 1. GLASS ORDER SHEET HEADER STYLING
            // --------------------------------------------
            $titleRange = 'A1:R1';
            $headerRange = 'A2:R2';

            // Merge "Glass Order Sheet" title
            $event->sheet->mergeCells($titleRange);

            // Title styling (centered bold)
            $event->sheet->getDelegate()->getStyle($titleRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color'    => ['argb' => 'FFFFFFFF'], // white background
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => 'FFFF0000'], // red border
                    ],
                ],
            ]);

            // Table header styling (A2:Q2)
            $event->sheet->getDelegate()->getStyle($headerRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FF000000'], // black text
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => 'FFFF0000'],
                    ],
                ],
            ]);

            // Auto-size all columns A–R
            foreach (range('A', 'R') as $col) {
                $event->sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // --------------------------------------------
            // 🔹 2. SUMMARY SECTION STYLING
            // --------------------------------------------
            $highestRow = $event->sheet->getDelegate()->getHighestRow();
            $summaryRow = null;
            for ($r = 1; $r <= $highestRow; $r++) {
                $val = $event->sheet->getDelegate()->getCell("A{$r}")->getValue();
                if (trim($val) === 'Summary') {
                    $summaryRow = $r;
                    break;
                }
            }

            if ($summaryRow) {
                // Summary title row styling (yellow bar)
                $summaryTitle = "A{$summaryRow}:R{$summaryRow}";
                $event->sheet->mergeCells($summaryTitle);
                $event->sheet->getDelegate()->getStyle($summaryTitle)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color'    => ['argb' => 'FFFFE699'], // light yellow
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FFFF0000'], // red border
                        ],
                    ],
                ]);

                // Summary header row ("Glass Type", etc.)
                $summaryHeader = $summaryRow + 1;
                $summaryHeaderRange = "A{$summaryHeader}:R{$summaryHeader}";
                $event->sheet->getDelegate()->getStyle($summaryHeaderRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF9C0006'], // dark red
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],
                ]);
            }
        },
    ];
}



    public function title(): string
    {
        return 'Glass Aperture Only';
    }
}
