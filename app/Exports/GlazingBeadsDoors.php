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
use App\Models\User;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class GlazingBeadsDoors implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id,$vid,$result;

    function __construct($id,$vid,$result) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    public function collection()
    {
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id', $this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.GlazingBeadSpecies')->where('QuotationId', $this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

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
            if ($value->GlazingBeads != '' && $value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 ){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                if(!empty($allSettings['VPBead.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['VPBead.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['VPBead.NRF']->Height;
                }
                if(!empty($allSettings['VPBead.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['VPBead.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['VPBead.FD60']->Height;
                }
                $data[] = array(
                    $value->DoorType,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $value->GlazingBeadsThickness,
                    $value->glazingBeadsHeight,

                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->Leaf1VPWidth + $VisionPanelWidthNFR) : ($value->Leaf1VPWidth + $VisionPanelWidthFD60),
                    $value->VisionPanelQuantity * 4,

                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight1 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight1 + $VisionPanelHeightNFR,
                    $value->Leaf1VPHeight1 ? 4 : '',
                    $value->Leaf1VPHeight2 ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight2 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight2 + $VisionPanelHeightNFR) : '',
                    $value->Leaf1VPHeight2 ? 4 : '',
                    $value->Leaf1VPHeight3 ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight3 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight3 + $VisionPanelHeightNFR) : '',
                    $value->Leaf1VPHeight3 ? 4 : '',
                    $value->Leaf1VPHeight4 ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight4 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight4 + $VisionPanelHeightNFR) : '',
                    $value->Leaf1VPHeight4 ? 4 : '',
                    $value->Leaf1VPHeight5 ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight5 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight5 + $VisionPanelHeightNFR) : '',
                    $value->Leaf1VPHeight5 ? 4 : '',
                );


                $k++;
            }

            if($value->Overpanel == 'Fan_Light'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                if(!empty($allSettings['FanlightBead.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['FanlightBead.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['FanlightBead.NRF']->Height;
                }
                if(!empty($allSettings['FanlightBead.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['FanlightBead.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['FanlightBead.FD60']->Height;
                }
                $data[] = array(
                    $value->DoorType. ' ' .$value->Overpanel,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $value->GlazingBeadsThickness,
                    $value->glazingBeadsHeight,
                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->OPWidth + $VisionPanelWidthNFR) : ($value->OPWidth + $VisionPanelWidthFD60),
                    '',
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->OPHeight + $VisionPanelHeightFD60 : $value->OPHeight + $VisionPanelHeightNFR,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }

            if($value->SideLight1 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                if(!empty($allSettings['SideBead.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['SideBead.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideBead.NRF']->Height;
                }
                if(!empty($allSettings['SideBead.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideBead.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideBead.FD60']->Height;
                }
                $data[] = array(
                    $value->DoorType. ' Side Light 1',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $value->GlazingBeadsThickness,
                    $value->glazingBeadsHeight,
                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->SL1Width + $VisionPanelWidthNFR) : ($value->SL1Width + $VisionPanelWidthFD60),

                    4,

                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->SL1Height + $VisionPanelHeightFD60 : $value->SL1Height + $VisionPanelHeightNFR,

                    4,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }

            if($value->SideLight2 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                if(!empty($allSettings['SideBead.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['SideBead.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideBead.NRF']->Height;
                }
                if(!empty($allSettings['SideBead.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideBead.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideBead.FD60']->Height;
                }
                $data[] = array(
                    $value->DoorType. ' Side Light 2',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $value->GlazingBeadsThickness,
                    $value->glazingBeadsHeight,
                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->SL2Width + $VisionPanelWidthNFR) : ($value->SL2Width + $VisionPanelWidthFD60),
                    4,
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->SL2Height + $VisionPanelHeightFD60 : $value->SL2Height + $VisionPanelHeightNFR,
                    4,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }
        }
       // ===================== SUMMARY SECTION ==========================
        $summary = [];

        foreach ($data as $row) {
            if (!isset($row[4]) || empty($row[4])) continue; // skip blanks

            $species = $row[4];
            $profile = $row[5];
            $height  = $row[7] ?? 'N/A';
            $depth   = $row[8] ?? 'N/A';
            $width   = $row[9] ?? 'N/A';
            $length  = $row[11] ?? 'N/A';  // was $hgt
            // $row[10] is the per-row Qty (often 4). We are NOT summing it anymore.

            $key = "{$species}|{$profile}|{$height}|{$depth}|{$width}x{$length}";

            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'species' => $species,
                    'profile' => $profile,
                    'height'  => $height,
                    'depth'   => $depth,
                    'width'   => $width,
                    'length'  => $length,
                    'count'   => 0,        // count occurrences (rows)
                ];
            }

            // 🔧 CHANGED: count rows/occurrences instead of summing Qty pieces
            $summary[$key]['count'] += 1;
        }

        // blank row
        $data[] = array_fill(0, 15, '');

        // summary heading
        $data[] = ['Summary', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = [
            'Glazing Bead Species',
            'Glazing Bead Profile',
            'Glazing Bead Height',
            'Glazing Bead Depth',
            'Glazing Bead Width',
            'Glazing Bead Length',
            'Count',
        ];

        // summary rows
        foreach ($summary as $row) {
            $data[] = [
                $row['species'],
                $row['profile'],
                $row['height'],
                $row['depth'],
                $row['width'],
                $row['length'],
                $row['count'],   // now 2 for your example
            ];
        }

        $footData = ['','','','','','','','','','','','','',''];
        $allData  = [$data, $footData];

        return collect($allData);


    }

    public function headings(): array
    {
        $a = [
        'Door Ref', 'Door Type', 'Plot Number/Ref','IFC/Certifire No/Q mark Plug','Timber', 'Profile','Finish on Bead',
        'Glazing Bead Height', 'Glazing Bead Depth',
        'VP1 W', 'QTY', 'VP1 H', 'QTY', 'VP2 H', 'QTY',
        'VP3 H', 'QTY', 'VP4 H', 'QTY', 'VP5 H', 'QTY',
        ];


        $b = ['Glazing Beads for Doors'];

        $d = [$b,$a];
        return $d;
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // ===== MAIN HEADER =====
            $titleRange = 'A1:U1';
            $sheet->mergeCells($titleRange);
            $sheet->setCellValue('A1', 'Glazing Beads for Doors');

            $titleStyle = [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FF0000'],
                    ],
                ],
            ];

            $headerStyle = [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FF0000'],
                    ],
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FF0000'],
                    ],
                ],
            ];

            $sheet->getStyle($titleRange)->applyFromArray($titleStyle);
            $sheet->getStyle('A2:U2')->applyFromArray($headerStyle);

            foreach (range('A', 'U') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // ===== SUMMARY STYLING =====
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn(); // e.g. "F" or "U"
            $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

            for ($row = 1; $row <= $highestRow; $row++) {
                $cellValue = trim((string) $sheet->getCell("A{$row}")->getValue());
                if (strtolower($cellValue) === 'summary') {

                    // 🔹 Find last non-empty cell in this row’s next few lines
                    $lastUsedCol = 'A';
                    for ($col = 1; $col <= $highestColIndex; $col++) {
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                        if (!empty(trim((string) $sheet->getCell("{$colLetter}" . ($row + 1))->getValue()))) {
                            $lastUsedCol = $colLetter;
                        }
                    }

                    // 🔸 Merge Summary only till the last active column (e.g. A:F)
                    $sheet->mergeCells("A{$row}:{$lastUsedCol}{$row}");

                    // 🔸 Apply background color
                    $sheet->getStyle("A{$row}:{$lastUsedCol}{$row}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFF2CC'],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FF0000'],
                            ],
                        ],
                    ]);

                    // 🔸 Header line below summary
                    $nextRow = $row + 1;
                    $sheet->getStyle("A{$nextRow}:{$lastUsedCol}{$nextRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['argb' => 'FF0000'],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color' => ['argb' => 'FF0000'],
                            ],
                        ],
                    ]);
                    break;
                }
            }
        },
    ];
}





    public function title(): string
    {
        return 'Glazing Beads for Doors';
    }
}
