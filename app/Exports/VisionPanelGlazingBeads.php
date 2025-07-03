<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\Quotation;

class VisionPanelGlazingBeads implements FromCollection,WithEvents,WithTitle
{
    protected $id,$vid,$result;

    function __construct($id,$vid,$result) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    // public function collection()
    // {
    //     $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id', $this->id)->first();

    //     $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.GlazingBeadSpecies')->where('QuotationId', $this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

    //     $k = 1;
    //     $data = [];
    //     $data[] = array_fill(0, 19, '');
    //     $data[] = array_merge(['Vision Panel Glazing Beads'], array_fill(0, 19, ''));
    //     $data[] = [
    //         'Door Ref',
    //         'Door Type',
    //         'Timber',
    //         'Profile',
    //         'Glazing Bead Height',
    //         'Glazing Bead Depth',
    //         'Finish on Bead',
    //         'VP1 W',
    //         'QTY',
    //         'VP1 H',
    //         'QTY',
    //         'VP2 H',
    //         'QTY',
    //         'VP3 H',
    //         'QTY',
    //         'VP4 H',
    //         'QTY',
    //         'VP5 H',
    //         'QTY',
    //     ];
    //     foreach($item as $value){
    //         if ($value->GlazingBeads != '' && $value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 && $value->Leaf1VisionPanel == 'Yes'){
    //                 $LeafVPHeightQty = $value->VisionPanelQuantity * 4;

    //             $data[] = array(
    //                 $value->doorNumber,
    //                 $value->DoorType,
    //                 $value->SpeciesName,
    //                 str_replace('_', ' ', $value->GlazingBeads),
    //                 $value->GlazingBeadsThickness,
    //                 $value->glazingBeadsHeight,
    //                 str_replace('_', ' ', $value->DoorLeafFinish),
    //                 $value->Leaf1VPWidth,
    //                 $LeafVPHeightQty,
    //                 $value->Leaf1VPHeight1 ?? '',
    //                 $value->Leaf1VPHeight1 ? 4 : '',
    //                 $value->Leaf1VPHeight2 ?? '',
    //                 $value->Leaf1VPHeight2 ? 4 : '',
    //                 $value->Leaf1VPHeight3 ?? '',
    //                 $value->Leaf1VPHeight3 ? 4 : '',
    //                 $value->Leaf1VPHeight4 ?? '',
    //                 $value->Leaf1VPHeight4 ? 4 : '',
    //                 $value->Leaf1VPHeight5 ?? '',
    //                 $value->Leaf1VPHeight5 ? 4 : '',
    //             );

    //             $k++;
    //         }
    //     }

    //     $data[] = array_fill(0, 19, '');
    //     $data[] = array_fill(0, 19, '');
    //     $data[] = array_fill(0, 19, '');

    //     $data[] = array_merge(['Side Light Glazing Beads'], array_fill(0, 13, ''));

    //     $data[] = [
    //         'Door Ref',
    //         'Door Type',
    //         'Timber',
    //         'Profile',
    //         'Glazing Bead Height',
    //         'Glazing Bead Depth',
    //         'Finish on Bead',
    //         'SL1 W',
    //         'QTY',
    //         'SL1 H',
    //         'QTY',
    //         'SL2 H',
    //         'QTY',
    //     ];

    //     foreach($item as $value){
    //         if ($value->SideLight1 == 'Yes' || $value->SideLight2 == 'Yes'){
    //             $data[] = array(
    //                 $value->doorNumber,
    //                 $value->DoorType,
    //                 $value->SpeciesName,
    //                 str_replace('_', ' ', $value->GlazingBeads),
    //                 $value->SideLight1GlazingBeadsThickness,
    //                 $value->SideLight1GlazingBeadsWidth,
    //                 str_replace('_', ' ', $value->DoorLeafFinish),
    //                 $value->SL1Width,
    //                 4,
    //                 $value->SL1Height,
    //                 4,
    //                 $value->SL2Width,
    //                 4
    //             );

    //             $k++;
    //         }
    //     }

    //     $data[] = array_fill(0, 13, '');
    //     $data[] = array_fill(0, 13, '');
    //     $data[] = array_fill(0, 13, '');

    //     $data[] = array_merge(['Fan Light Glazing Beads'], array_fill(0, 11, ''));
    //     $data[] = [
    //         'Door Ref',
    //         'Door Type',
    //         'Timber',
    //         'Profile',
    //         'Glazing Bead Height',
    //         'Glazing Bead Depth',
    //         'Finish on Bead',
    //         'FL1 W',
    //         'QTY',
    //         'FL1 H',
    //         'QTY',
    //     ];

    //     foreach($item as $value){
    //         if ($value->Overpanel == 'Fan_Light'){
    //             $data[] = array(
    //                 $value->doorNumber,
    //                 $value->DoorType,
    //                 $value->SpeciesName,
    //                 str_replace('_', ' ', $value->OPGlazingBeads),
    //                 $value->OPGlazingBeadsThickness,
    //                 $value->OPGlazingBeadsHeight,
    //                 str_replace('_', ' ', $value->DoorLeafFinish),
    //                 $value->OPWidth,
    //                 4,
    //                 $value->OPHeigth,
    //                 4
    //             );

    //             $k++;
    //         }
    //     }



    //     // $footData = [
    //     //     '','','','','','','','','','','','','','','','','','',''
    //     // ];

    //     $allData = [$data];

    //     return collect($allData);
    // }

    // public function headings(): array
    // {
    //     $a = [
    //         'Door Ref',
    //         'Door Type',
    //         'Timber',
    //         'Profile',
    //         'Glazing Bead Height',
    //         'Glazing Bead Depth',
    //         'Finish on Bead',
    //         'VP1 W',
    //         'QTY',
    //         'VP1 H',
    //         'QTY',
    //         'VP2 H',
    //         'QTY',
    //         'VP3 H',
    //         'QTY',
    //         'VP4 H',
    //         'QTY',
    //         'VP5 H',
    //         'QTY',
    //     ];


    //     $b = ['Vision Panel Glazing Beads'];

    //     $d = [$b,$a];
    //     return $d;
    // }

    public $rowPositions = []; // Add this at the top of your export class

public function collection()
{
    $quotation = Quotation::select(
        'project.*',
        'quotation.*',
        'customers.CstCompanyName',
        'project.ProjectName as projectname'
    )
    ->leftJoin('project', 'quotation.ProjectId', '=', 'project.id')
    ->leftJoin('customers', 'customers.UserId', 'quotation.MainContractorId')
    ->where('quotation.id', $this->id)
    ->first();

    $item = Item::join('item_master', 'items.itemId', 'item_master.itemID')
        ->leftJoin('lipping_species', 'lipping_species.id', 'items.GlazingBeadSpecies')
        ->where('QuotationId', $this->id)
        ->where('VersionId', $this->vid)
        ->select('item_master.*', 'items.*', 'lipping_species.SpeciesName')
        ->orderBy('items.itemId', 'ASC')
        ->get();

    $data = [];
    $rowNum = 1;

    // Vision Panel Section
    $data[] = array_fill(0, 19, '');
    $rowNum++;

    $data[] = array_merge(['Vision Panel Glazing Beads'], array_fill(0, 18, ''));
    $this->rowPositions['vp_title'] = $rowNum;
    $rowNum++;

    $data[] = [
        'Door Ref', 'Door Type', 'Timber', 'Profile',
        'Glazing Bead Height', 'Glazing Bead Depth', 'Finish on Bead',
        'VP1 W', 'QTY', 'VP1 H', 'QTY', 'VP2 H', 'QTY',
        'VP3 H', 'QTY', 'VP4 H', 'QTY', 'VP5 H', 'QTY',
    ];
    $this->rowPositions['vp_head'] = $rowNum;
    $rowNum++;

    foreach ($item as $value) {
        if (
            $value->GlazingBeads != '' &&
            $value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0 &&
            $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 &&
            $value->Leaf1VisionPanel == 'Yes'
        ) {
            $LeafVPHeightQty = $value->VisionPanelQuantity * 4;

            $data[] = [
                $value->doorNumber,
                $value->DoorType,
                $value->SpeciesName,
                str_replace('_', ' ', $value->GlazingBeads),
                $value->GlazingBeadsThickness,
                $value->glazingBeadsHeight,
                str_replace('_', ' ', $value->DoorLeafFinish),
                $value->Leaf1VPWidth,
                $LeafVPHeightQty,
                $value->Leaf1VPHeight1 ?? '',
                $value->Leaf1VPHeight1 ? 4 : '',
                $value->Leaf1VPHeight2 ?? '',
                $value->Leaf1VPHeight2 ? 4 : '',
                $value->Leaf1VPHeight3 ?? '',
                $value->Leaf1VPHeight3 ? 4 : '',
                $value->Leaf1VPHeight4 ?? '',
                $value->Leaf1VPHeight4 ? 4 : '',
                $value->Leaf1VPHeight5 ?? '',
                $value->Leaf1VPHeight5 ? 4 : '',
            ];
            $rowNum++;
        }
    }

    $data[] = array_fill(0, 19, '');
    $data[] = array_fill(0, 19, '');
    $data[] = array_fill(0, 19, '');
    $rowNum += 3;

    // Side Light Section
    $data[] = array_merge(['Side Light Glazing Beads'], array_fill(0, 12, ''));
    $this->rowPositions['sl_title'] = $rowNum;
    $rowNum++;

    $data[] = [
        'Door Ref', 'Door Type', 'Timber', 'Profile',
        'Glazing Bead Height', 'Glazing Bead Depth', 'Finish on Bead',
        'SL1 W', 'QTY', 'SL1 H', 'QTY', 'SL2 H', 'QTY'
    ];
    $this->rowPositions['sl_head'] = $rowNum;
    $rowNum++;

    foreach ($item as $value) {
        if ($value->SideLight1 == 'Yes' || $value->SideLight2 == 'Yes') {
            $data[] = [
                $value->doorNumber,
                $value->DoorType,
                $value->SpeciesName,
                str_replace('_', ' ', $value->GlazingBeads),
                $value->SideLight1GlazingBeadsThickness,
                $value->SideLight1GlazingBeadsWidth,
                str_replace('_', ' ', $value->DoorLeafFinish),
                $value->SL1Width,
                4,
                $value->SL1Height,
                4,
                $value->SL2Width,
                4
            ];
            $rowNum++;
        }
    }

    $data[] = array_fill(0, 13, '');
    $data[] = array_fill(0, 13, '');
    $data[] = array_fill(0, 13, '');
    $rowNum += 3;

    // Fan Light Section
    $data[] = array_merge(['Fan Light Glazing Beads'], array_fill(0, 10, ''));
    $this->rowPositions['fl_title'] = $rowNum;
    $rowNum++;

    $data[] = [
        'Door Ref', 'Door Type', 'Timber', 'Profile',
        'Glazing Bead Height', 'Glazing Bead Depth', 'Finish on Bead',
        'FL1 W', 'QTY', 'FL1 H', 'QTY'
    ];
    $this->rowPositions['fl_head'] = $rowNum;
    $rowNum++;

    foreach ($item as $value) {
        if ($value->Overpanel == 'Fan_Light') {
            $data[] = [
                $value->doorNumber,
                $value->DoorType,
                $value->SpeciesName,
                str_replace('_', ' ', $value->OPGlazingBeads),
                $value->OPGlazingBeadsThickness,
                $value->OPGlazingBeadsHeight,
                str_replace('_', ' ', $value->DoorLeafFinish),
                $value->OPWidth,
                4,
                $value->OPHeigth,
                4
            ];
            $rowNum++;
        }
    }

    return collect([$data]);
}

   public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            $pos = $this->rowPositions;

            // Dynamic title and heading row ranges
            $titles = [
                "A{$pos['vp_title']}:S{$pos['vp_title']}",
                "A{$pos['sl_title']}:M{$pos['sl_title']}",
                "A{$pos['fl_title']}:L{$pos['fl_title']}",
            ];

            $headings = [
                "A{$pos['vp_head']}:S{$pos['vp_head']}",
                "A{$pos['sl_head']}:M{$pos['sl_head']}",
                "A{$pos['fl_head']}:L{$pos['fl_head']}",
            ];

            $titleStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FF000000'],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FF0000'],
                    ],
                ],
            ];

            $headingStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FF000000'],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            // Merge and style titles
            foreach ($titles as $range) {
                $event->sheet->mergeCells($range);
                $event->sheet->getStyle($range)->applyFromArray($titleStyle);
            }

            // Style headings
            foreach ($headings as $range) {
                $event->sheet->getStyle($range)->applyFromArray($headingStyle);
                $event->sheet->getStyle($range)->getAlignment()->setWrapText(true);
            }

            // Auto-size columns A to S
            foreach (range('A', 'S') as $col) {
                $event->sheet->getColumnDimension($col)->setAutoSize(true);
            }
        },
    ];
}




    public function title(): string
    {
        return 'Glazing Beads';
    }
}
