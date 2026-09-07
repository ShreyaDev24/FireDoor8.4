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

class Ironmongery implements FromCollection,WithHeadings,WithEvents,WithTitle,WithColumnFormatting
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
        $aa = [];
        $data = [];
        $cat = [];

        foreach($this->result['data'] as $value){
            if($value->Category=='Ironmongery&MachiningCosts'){
                $words = explode("|", (string) $value->Description);
                $doortype = $words[0] ?? "";
                $words1 = $words[1] ?? "";
                $words2 = $words[2] ?? "";
                $words3 = $words[3] ?? "";
                $words4 = $words[4] ?? "";
                $words5 = $words[count($words) - 2] ?? null;
                $quantity = $value->LMPerDoorType;
                $words6 = floatval(($words[count($words) - 1]) ?? 0);
                $margin = $value->Margin;

                $marginwithcal = 100 - $margin;
                $testvar = $marginwithcal / 100;
                $totalcost = $words6 / $testvar;

                $data[] = [
                    $doortype,   // Door Type (new first column)
                    $words1,
                    $words2,
                    $words3,
                    $words4,
                    $words5,
                    $quantity,
                    $totalcost

                ];

                $cat[] = $words1;
            }
        }


        $category = array_unique($cat);

        foreach ($category as $cate) {
            foreach ($data as $val) {
                if ($cate == $val[1]) {
                    // $val[3] represents ironmongery name
                    if (isset($aa[$cate][$val[4]])) {
                        $aa[$cate][$val[4]] += $val[6]; // Increment quantity if ironmongery name exists
                    } else {
                        $aa[$cate][$val[4]] = $val[6]; // Otherwise, set the quantity
                    }
                }
            }
        }


        $val = [];

        // Loop through each element in the $aa array
        foreach ($aa as $ke => $v) {
            foreach ($v as $key => $value) {

                // Collect all door types for this category+ironmongery
                $doorTypes = [];
                $firstMatch = null;

                foreach ($data as $valu) {
                    if ($valu[4] === $key && $ke === $valu[1]) {
                        $doorTypes[] = $valu[0];   // add door type
                        if ($firstMatch === null) {
                            $firstMatch = $valu;   // save first row as reference
                        }
                    }
                }

                if ($firstMatch) {
                    $val[] = [
                        implode(", ", array_unique($doorTypes)), // Concatenate door types
                        $firstMatch[1],   // Category
                        $firstMatch[2],   // Code
                        $firstMatch[3],   // Ironmongery Name
                        $firstMatch[4],   // Supplier
                        $firstMatch[5],   // Extra field (if needed)
                        $value,           // Quantity
                        $value * $firstMatch[7], // Price
                    ];
                }
            }
        }


        // Store the resulting array inside another array
        $allData = [$val];

        // Convert the resulting array into a collection and return it
        return collect($allData);


    }

    public function headings(): array
   {
    // Define an array $a containing column headings
    $a = [
        'Door Type',
        'Ironmongery Set Name',
        'Category',
        'Code',
        'Name',
        'Supplier',
        'Quantity',
        'Total Cost',
    ];

    // Define an array $b containing a single element 'Ironmongery'
    $b = ['Ironmongery'];

    // Combine the arrays $b and $a into a nested array $d
    $d = [$b, $a];

    // Return the nested array $d containing the headings
    return $d;
}


    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function(AfterSheet $event): void {
                $headerRange1 = 'A1:H1';
                $headerRange2 = 'A2:H2';
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color' => '#000000'
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

                // Merge header cells
                $event->sheet->mergeCells($headerRange1);

                // Set columns to auto size
                foreach (range('A', 'H') as $columnID) {
                    $event->sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                // Apply style and wrap text for the second header range
                $event->sheet->getStyle($headerRange2)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($headerRange2)->applyFromArray($styleArray);

                // Apply style for the first header range
                $event->sheet->getDelegate()->getStyle($headerRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Ironmongery';
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
                'H' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'H' => $currencyFormats['£'],
            ];
        } else {
            return [
                'H' => $currencyFormats['€'],
            ];
        }

    }
}
