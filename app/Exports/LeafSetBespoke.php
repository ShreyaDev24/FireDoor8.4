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

class LeafSetBespoke implements FromCollection,WithHeadings,WithEvents,WithTitle,WithColumnFormatting
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
        $currency = $this->result['currency'];
        $total = $GTSell = 0;

        $j = 1;
        $data = [];
        foreach($this->result['data'] as $value){
            if($value->Category=='LeafSetBesPoke'){
                $total = $total + $value->TotalCost;
                $GTSell = $GTSell + $value->GTSellPrice;
                $words = explode("|", $value->Description);
                $doortype = isset($words[0])? $words[0] : "";
                $words1 = isset($words[1])? $words[1] : "";
                $words2 = isset($words[2])? $words[2] : "";
                $words3 = isset($words[3])? $words[3] : "";
                $words4 = isset($words[4])? $words[4] : "";
                $words5 = isset($words[5])? $words[5] : "";
                $QuantityOfDoorTypes = $value->QuantityOfDoorTypes;
                $Unit = $value->Unit;
                $UnitCost = $value->UnitCost;
                $TotalCost = round($value->TotalCost, 2);
                $UnitPriceSell = $value->UnitPriceSell;
                $GTSellPrice = $value->GTSellPrice;
                $Margin = $value->Margin.'%';

                $data[] = array(
                    $j,
                    $doortype,
                    $words1,
                    $words2,
                    $words3,
                    $words4,
                    $words5,
                    $QuantityOfDoorTypes,
                    $Unit,
                    $UnitCost,
                    $TotalCost,
                    $UnitPriceSell,
                    $GTSellPrice,
                    $Margin
                );
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
        if($this->result['quotation']->configurableitems == 4 || $this->result['quotation']->configurableitems == 5 || $this->result['quotation']->configurableitems == 6 || $this->result['quotation']->configurableitems == 5){
            $a = [
                'S.No',
                'Door Type',
                'Door Core',
                'Lipping Type',
                'Lipping Thickness/Lipping Species',
                'Door Leaf Size',
                'Door Dimensions Code',
                'Total Quantity',
                'Unit',
                'Unit Cost',
                'Total Cost',
                'Unit Price Sell ',
                'GT Sell Price',
            ];
        }else{
            $a = [
                'S.No',
                'Door Type',
                'Door Core',
                'Lipping Type',
                'Lipping Thickness',
                'Lipping Species',
                'Door Leaf Size',
                'Total Quantity',
                'Unit',
                'Unit Cost',
                'Total Cost',
                'Unit Price Sell ',
                'GT Sell Price',
            ];
        }

        foreach($this->result['data'] as $value){
            $MarginMarkup = $value->MarginMarkup;
        }
        $b  = ['Door Details'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event) {
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
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Door Details';
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
                'J' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'K' => $currencyFormats['£'],
                'L' => $currencyFormats['£'],
                'M' => $currencyFormats['£'],
                'J' => $currencyFormats['£'],
            ];
        } else {
            return [
                'K' => $currencyFormats['€'],
                'L' => $currencyFormats['€'],
                'M' => $currencyFormats['€'],
                'J' => $currencyFormats['€'],
            ];
        }

    }
}
