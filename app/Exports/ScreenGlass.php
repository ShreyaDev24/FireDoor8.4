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
use App\Models\QuotationVersion;
use App\Models\BOMCalculation;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\DoorFrameConstruction;
use App\Models\User;
use Auth;

class ScreenGlass implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    public function __construct(
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $id,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $vid,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $result
    )
    {
    }

    public function collection()
    {
        $k = 1;
        $data = [];
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }
        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        foreach($this->result as $value){
            if(empty($value->TransomQuantity)){
                $value->TransomQuantity = 0;
            }

            if(empty($value->MullionQuantity)){
                $value->MullionQuantity = 0;
            }
            $ScreenGlassWidthNFR = $ScreenGlassHeightNFR = $ScreenGlassWidthFD60 = $ScreenGlassHeightFD60 = 0;
            if(!empty($allSettings['ScreenGlass.NFR'])){
                $ScreenGlassWidthNFR = $allSettings['ScreenGlass.NFR']->Width;
                $ScreenGlassHeightNFR = $allSettings['ScreenGlass.NFR']->Height;
            }
            if(!empty($allSettings['ScreenGlass.FD60'])){
                $ScreenGlassWidthFD60 = $allSettings['ScreenGlass.FD60']->Width;
                $ScreenGlassHeightFD60 = $allSettings['ScreenGlass.FD60']->Height;
            }

            $FrameHeight = $value->FrameHeight;
            $FrameWidth = $value->FrameWidth;
            $FrameThickness = $value->FrameThickness;



            $TransomQuantity = $value->TransomQuantity + 1;
            $MullionQuantity = $value->MullionQuantity + 1;
            $alphabet = range('A', 'D'); // For row labels (A, B, C)
            for ($i = 0; $i < $TransomQuantity; $i++) {
                for ($j = 1; $j <= $MullionQuantity; $j++) {

                    $ScreenType = $value->ScreenType;
                    $glasspane = $alphabet[$i] . $j;
                    $glassType = $value->SinglePane;
                    // Map glass pane identifiers to their respective width and height properties
                    $glassPaneMap = [
                        'A1' => ['width' => 'GlassPane1Width', 'height' => 'GlassPane1Height'],
                        'A2' => ['width' => 'GlassPane2Width', 'height' => 'GlassPane2Height'],
                        'A3' => ['width' => 'GlassPane3Width', 'height' => 'GlassPane3Height'],
                        'A4' => ['width' => 'GlassPane4Width', 'height' => 'GlassPane4Height'],
                        'B1' => ['width' => 'GlassPane5Width', 'height' => 'GlassPane5Height'],
                        'B2' => ['width' => 'GlassPane6Width', 'height' => 'GlassPane6Height'],
                        'B3' => ['width' => 'GlassPane7Width', 'height' => 'GlassPane7Height'],
                        'B4' => ['width' => 'GlassPane8Width', 'height' => 'GlassPane8Height'],
                        'C1' => ['width' => 'GlassPane9Width', 'height' => 'GlassPane9Height'],
                        'C2' => ['width' => 'GlassPane10Width', 'height' => 'GlassPane10Height'],
                        'C3' => ['width' => 'GlassPane11Width', 'height' => 'GlassPane11Height'],
                        'C4' => ['width' => 'GlassPane12Width', 'height' => 'GlassPane12Height'],
                        'D1' => ['width' => 'GlassPane13Width', 'height' => 'GlassPane13Height'],
                        'D2' => ['width' => 'GlassPane14Width', 'height' => 'GlassPane14Height'],
                        'D3' => ['width' => 'GlassPane15Width', 'height' => 'GlassPane15Height'],
                        'D4' => ['width' => 'GlassPane16Width', 'height' => 'GlassPane16Height'],
                        // Add more mappings as needed
                    ];

                    // Check if the glass pane exists in the map
                    if (isset($glassPaneMap[$glasspane])) {
                        if($alphabet[$i] == 'A'){
                            $glassType = $value->SinglePane;
                        }else if($alphabet[$i] == 'B'){
                            $glassType = (isset($value->SinglePaneB))?$value->SinglePaneB:$value->SinglePane;
                        }else if($alphabet[$i] == 'C'){
                            $glassType = (isset($value->SinglePaneC))?$value->SinglePaneC:$value->SinglePane;
                        }else if($alphabet[$i] == 'D'){
                            $glassType = (isset($value->SinglePaneD))?$value->SinglePaneD:$value->SinglePane;
                        }
                        $GlassPaneWidth = $value->{$glassPaneMap[$glasspane]['width']};
                        $GlassPaneHeight = $value->{$glassPaneMap[$glasspane]['height']};
                    }else {
                        $GlassPaneWidth = 0;
                        $GlassPaneHeight = 0;
                    }

                    if($value->FireRating == '60-60' || $value->FireRating == '60-0'){
                        $GlassWidth = $GlassPaneWidth + $ScreenGlassWidthFD60;
                        $GlassHeight = $GlassPaneHeight + $ScreenGlassHeightFD60;
                    }else{
                        $GlassWidth = $GlassPaneWidth + $ScreenGlassWidthNFR;
                        $GlassHeight = $GlassPaneHeight + $ScreenGlassHeightNFR;
                    }

                    $screenQty = 1;
                    $screenNumber = $value->screenNumber;

                    $data[] = [
                        $k,
                        $value->plot_ref_no,
                        $value->certification_no,
                        $screenNumber,
                        $ScreenType,
                        $glasspane,
                        $glassType,
                        $GlassWidth,
                        $GlassHeight,
                        $screenQty,
                    ];
                    $k++;
                }
            }
        }

         // ===================== SUMMARY =====================
    $summary = [];
    foreach ($data as $row) {
        if (empty($row[6])) continue; // Glass Type column

        $type = $row[6];
        $width = $row[7] ?? 0;
        $height = $row[8] ?? 0;
        $qty = (int)($row[9] ?? 0);

        $key = "{$type}|{$width}|{$height}";
        if (!isset($summary[$key])) {
            $summary[$key] = [
                'Glass Type' => $type,
                'Glass Width' => $width,
                'Glass Height' => $height,
                'QTY' => 0
            ];
        }
        $summary[$key]['QTY'] += $qty;
    }

    // add blank row
    $data[] = array_fill(0, 10, '');

    $data[] = array_fill(0, 10, '');
    $data[] = array_fill(0, 10, '');
    $data[] = array_fill(0, 10, '');

    // add summary header
    $data[] = ['Summary', '', '', '', '', '', '', '', '', ''];
    $data[] = ['Glass Type', 'Glass Width', 'Glass Height', 'QTY'];

    // add summary rows
    foreach ($summary as $row) {
        $data[] = [
            $row['Glass Type'],
            $row['Glass Width'],
            $row['Glass Height'],
            $row['QTY']
        ];
    }

    $footData = array_fill(0, 10, '');
    $allData = [$data, $footData];

    return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'S.No',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Screen Number',
            'Screen Type',
            'Glass Panes ',
            'Glass Type',
            'Glass Width',
            'Glass Height ',
            'Quantity of Screen  types'
        ];
        $b = ['Screen Glass'];

        $d = [$b,$a];
        return $d;
    }

public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event): void {
            $sheet = $event->sheet->getDelegate();

            // ===== Main header design (same as before) =====
            $sheet->mergeCells('A1:J1');
            $sheet->getStyle('A1:J2')->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => 'FFFF0000'],
                    ],
                ],
            ]);

            foreach (range('A','J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // ===== Style the summary header (A:D) =====
            $highestRow = $sheet->getHighestRow();
            for ($r = 1; $r <= $highestRow; $r++) {
                if (trim((string)$sheet->getCell("A{$r}")->getValue()) === 'Summary') {

                    // Merge A:D for the Summary title
                    $sheet->mergeCells("A{$r}:D{$r}");

                    // Apply yellow fill + red border
                    $sheet->getStyle("A{$r}:D{$r}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFCE5CD');

                    $sheet->getStyle("A{$r}:D{$r}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FFFF0000'],
                            ],
                        ],
                    ]);

                    // Header row under summary (A:D)
                    $sheet->getStyle("A" . ($r+1) . ":D" . ($r+1))->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FFFF0000'],
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
        return 'Screen Glass';
    }
}
