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
use App\Models\SideScreenItemMaster;
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
        foreach($this->result as $value){
            if(empty($value->TransomQuantity)){
                $value->TransomQuantity = 0;
            }
            
            if(empty($value->MullionQuantity)){
                $value->MullionQuantity = 0;
            }

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
                        $GlassPaneWidth = $value->{$glassPaneMap[$glasspane]['width']};
                        $GlassPaneHeight = $value->{$glassPaneMap[$glasspane]['height']};
                    }else {
                        $GlassPaneWidth = 0;
                        $GlassPaneHeight = 0;
                    }

                    $screenQty = 1;
                    $screenNumber = $value->screenNumber;

                    $data[] = [
                        $k,
                        $screenNumber,
                        $ScreenType,
                        $glasspane,
                        $glassType,
                        $GlassPaneWidth,
                        $GlassPaneHeight,
                        $screenQty,
                    ];
                    $k++;
                }
            }
        }

        $footData = [
            '','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }
    
    public function headings(): array
    {
        $a = [
            'S.No',
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
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange1 = 'A1:H1';
                $cellRange = 'A2:H2';
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color'=> '#000000'
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
                $event->sheet->mergeCells($cellRange1);
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);

                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Screen Glass';
    }
}
