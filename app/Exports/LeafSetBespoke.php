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
        $currency = $this->result['currency'];
        $total = 0;
        $GTSell = 0;

        $j = 1;
        $k = 1;
        $data = [];
        $dataStandard = [];

        $customOrder = [1, 2, 7, 8, 4, 5, 6, 9];

        $sortedData = collect($this->result['data'])->sortBy(function ($item) use ($customOrder) {
            $position = array_search($item->configurableitems, $customOrder);

            return $position !== false ? $position : PHP_INT_MAX;
        });

        foreach($sortedData as $value){
            if($value->Category=='LeafSetBesPoke'){
                $total += $value->TotalCost;
                $GTSell += $value->GTSellPrice;
                $words = explode("|", (string) $value->Description);
                $doortype = $words[0] ?? "";
                $words1 = $words[1] ?? "";
                $words2 = $words[2] ?? "";
                $words3 = $words[3] ?? "";
                $words4 = $words[4] ?? "";
                $words5 = $words[5] ?? "";
                $QuantityOfDoorTypes = $value->QuantityOfDoorTypes;
                $Unit = $value->Unit;
                $UnitCost = $value->UnitCost;
                $TotalCost = round($value->TotalCost, 2);
                $UnitPriceSell = $value->UnitPriceSell;
                $GTSellPrice = $value->GTSellPrice;
                $Margin = $value->Margin.'%';

                if($value->configurableitems == 1 || $value->configurableitems == 2 || $value->configurableitems == 7 || $value->configurableitems == 9){
                    $data[] = [
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
                    ];
                    $j++;
                }else{
                    $dataStandard[] = [
                        $k,
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
                    ];
                    $k++;
                }

            }
        }

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

        foreach($this->result['data'] as $value){
            $MarginMarkup = $value->MarginMarkup;
        }

        $a[] = $MarginMarkup;

        $merged = array_merge($data, [array_fill(0, 20, '')], [$a], $dataStandard);

        $footData = [
            '','','','','','','','','','',$total ?? 0,'',$GTSell  ?? 0 ,''
        ];

        $allData = [$merged,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
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

        foreach($this->result['data'] as $value){
            $MarginMarkup = $value->MarginMarkup;
        }

        $a[] = $MarginMarkup;
        $b  = ['Door Details'];

        $d = [$b,$a];
        return $d;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // ----------------------------
                // 🔹 Existing header styling
                // ----------------------------
                $cellRange1 = 'A1:N1'; // main merged header
                $cellRange2 = 'A2:N2'; // column headings row


                $styleArray = [
                    'font' => ['bold' => true],
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

                // Merge and style top header
                $event->sheet->mergeCells($cellRange1);
                $event->sheet->getStyle($cellRange1)->applyFromArray($styleArray);
                $event->sheet->getStyle($cellRange2)->applyFromArray($styleArray);
                $event->sheet->getStyle($cellRange2)->getAlignment()->setWrapText(true);

                // Auto size all columns A–V
                foreach (range('A', 'N') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // ----------------------------
                // 🔹 New Summary Header Styling
                // ----------------------------
                $highestRow = $event->sheet->getHighestRow(); // last row on the sheet

                // Find the row number of "Summary" header (by searching for text)
                $summaryHeaderRow = null;
                foreach (range(1, $highestRow) as $r) {
                    $cellVal = trim((string)$event->sheet->getCell('G'.$r)->getValue());

                    if ($cellVal === 'Door Dimensions Code') {
                        $summaryHeaderRow = $r;
                        break;
                    }
                }

                if ($summaryHeaderRow) {
                    // Make the summary header bold, centered, with light gray fill
                    $event->sheet->getStyle('A' . $summaryHeaderRow . ':N' . $summaryHeaderRow)->applyFromArray([
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
                    ]);
                }
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
