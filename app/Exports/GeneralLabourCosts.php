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
use Auth;

class GeneralLabourCosts implements FromCollection,WithHeadings,WithEvents,WithTitle,WithColumnFormatting
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
        $total = 0;
        $GTSell = 0;
        $j = 1;
        $data = [];
        foreach($this->result['data'] as $value){
            if($value->Category=='GeneralLabourCosts'){
                $total += $value->TotalCost;
                $GTSell += $value->GTSellPrice;
                $words = explode("|", (string) $value->Description);
                $doortype = $words[0] ?? "";
                $words1 = $words[1] ?? "";
                $words2 = $words[2] ?? "";
                $words3 =$words[3] ?? "";
                $words4 = $words[4] ?? "";
                $words5 = $words[5] ?? "";
                $LMPerDoorType = $value->QuantityOfDoorTypes;
                $Unit = $value->Unit;
                $UnitCost = $value->UnitCost;
                $TotalCost = round($value->UnitCost * $value->QuantityOfDoorTypes,2);
                $UnitPriceSell = $value->UnitPriceSell;
                $GTSellPrice = $value->GTSellPrice;
                $Margin = $value->Margin.'%';

                $data[] = [
                    $j,
                    $doortype,
                    $words1,
                    $words2,
                    $words3,
                    $words4,
                    $words5,
                    $LMPerDoorType,
                    $Unit,
                    $UnitCost,
                    $TotalCost,
                    $UnitPriceSell,
                    $GTSellPrice,
                    $Margin
                ];
                $j++;
            }
        }

        $footData = [
            '','','','','','','','','','',$total ?? 0,'',$GTSell  ?? 0 ,''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }
    
    public function headings(): array
    {
        $a = [
            'S.No',
            'Door Type',
            'Labour Element',
            'MAN HOURS',
            'MAN Hour Rate',
            'MACHINE HOURS',
            'MACHINE Hour Rate',
            'Total Quantity',
            'Unit',
            'Unit Cost',
            'Total Cost',
            'Unit Price Sell ',
            'GT Sell Price',
        ];
        foreach($this->result['data'] as $value){
            $MarginMarkup = $value->MarginMarkup;
        }

        $a[] = $MarginMarkup;
        $b = ['General Labour Costs'];

        $d = [$b,$a];
        return $d;
    }
    
    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange1 = 'A1:N1';
                $cellRange = 'A2:N2';
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
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'General Labour Costs';
    }
    
    public function columnFormats(): array
    {
        $currencyFormats = [
            '$' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            '£' => '£#,##0.00',
            '€' => '€#,##0.00'
        ];

        // Apply the appropriate format based on the currency
        $currency = $this->result['currency'];
        $format = $currencyFormats[$currency] ?? NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE;

        if ($currency == '$') {
            return [
                'K' => $currencyFormats['$'],
                'L' => $currencyFormats['$'],
                'M' => $currencyFormats['$'],
                'J' => $currencyFormats['$'],
                // 'D' => $currencyFormats['$'],
                'E' => $currencyFormats['$'],
                // 'F' => $currencyFormats['$'],
                'G' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'K' => $currencyFormats['£'],
                'L' => $currencyFormats['£'],
                'M' => $currencyFormats['£'],
                'J' => $currencyFormats['£'],
                // 'D' => $currencyFormats['£'],
                'E' => $currencyFormats['£'],
                // 'F' => $currencyFormats['£'],
                'G' => $currencyFormats['£'],
            ];
        } else {
            return [
                'K' => $currencyFormats['€'],
                'L' => $currencyFormats['€'],
                'M' => $currencyFormats['€'],
                'J' => $currencyFormats['€'],
                // 'D' => $currencyFormats['€'],
                'E' => $currencyFormats['€'],
                // 'F' => $currencyFormats['€'],
                'G' => $currencyFormats['€'],
            ];
        }
    }
}
