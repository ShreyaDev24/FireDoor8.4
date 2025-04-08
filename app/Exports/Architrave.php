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

class Architrave implements FromCollection,WithHeadings,WithEvents,WithTitle,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $vid;

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $result;

    public function __construct($id,$vid,$result) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    public function collection()
    {
        $total = 0;
        $GTSell = 0;
        $j = 1;
        $data = [];
        foreach($this->result['data'] as $value){
            if($value->Category=='Architrave'){
                $total += $value->TotalCost;
                $GTSell += $value->GTSellPrice;
                $words = explode("|", $value->Description);
                $doortype = $words[0] ?? "";
                $words1 = $words[1] ?? "";
                $words2 = $words[2] ?? "";
                $words3 = $words[3] ?? "";
                $words4 = $words[4] ?? "";
                $words5 = $words[5] ?? "";
                // $words5 = $words[5];
                $LMPerDoorType = $value->LMPerDoorType;
                $QuantityOfDoorTypes = $value->QuantityOfDoorTypes;
                $Unit = $value->Unit;
                $UnitCost = $value->UnitCost;
                $TotalCost = round($value->TotalCost, 2);
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
                    $QuantityOfDoorTypes,
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
            '','','','','','','','','','','',$total ?? 0,'',$GTSell  ?? 0 ,''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }
    
    public function headings(): array
    {
        $a = [
            'S.No',
            'Door Type',
            'Architrave Size',
            'Architrave Type',
            'Architrave Material',
            'Architrave Finsih',
            'Set Qty',
            'LM Per Door Type',
            'Quantity of door types',
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
        $b = ['Architrave '];

        $d = [$b,$a];
        return $d;
    }
    
    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange1 = 'A1:O1';
                $cellRange = 'A2:O2';
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
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Architrave';
    }
    
    public function columnFormats(): array
    {
        $currencyFormats = [
            '$' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            '£' => '£#,##0.00',
            '€' => '€#,##0.00'
        ];

        // Get the currency from the result
        $currency = $this->result['currency'];

        // Select the appropriate format or default to EUR
        $format = $currencyFormats[$currency] ?? $currencyFormats['€'];

        // Apply the appropriate format based on the currency
        if ($currency == '$') {
            return [
                'K' => $currencyFormats['$'],
                'L' => $currencyFormats['$'],
                'M' => $currencyFormats['$'],
                'N' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'K' => $currencyFormats['£'],
                'L' => $currencyFormats['£'],
                'M' => $currencyFormats['£'],
                'N' => $currencyFormats['£'],
            ];
        } else {
            return [
                'K' => $currencyFormats['€'],
                'L' => $currencyFormats['€'],
                'M' => $currencyFormats['€'],
                'N' => $currencyFormats['€'],
            ];
        }

    }
}
