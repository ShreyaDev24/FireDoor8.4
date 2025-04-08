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
        $aa = [];
        $data = [];
        $cat = [];

        foreach($this->result['data'] as $value){
            if($value->Category=='Ironmongery&MachiningCosts'){
                $words = explode("|", $value->Description);
                $words1 = $words[1] ?? "";
                $words2 = $words[2] ?? "";
                $words3 = $words[3] ?? "";
                $words4 = $words[4] ?? "";
                $words5 = $words[5] ?? "";
                $quantity = $value->LMPerDoorType;
                $words6 = $words[6] ?? "";
                $margin = $value->Margin;

                $marginwithcal = 100 - $margin;
                $testvar = $marginwithcal / 100;
                $totalcost = $words6 / $testvar;

                $data[] = [
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
                if ($cate == $val[0]) {
                    // $val[3] represents ironmongery name
                    if (isset($aa[$cate][$val[3]])) {
                        $aa[$cate][$val[3]] += $val[5]; // Increment quantity if ironmongery name exists
                    } else {
                        $aa[$cate][$val[3]] = $val[5]; // Otherwise, set the quantity
                    }
                }
            }
        }


            $val = [];

            // Loop through each element in the $aa array
            foreach ($aa as $ke => $v) {
                // Loop through each nested array in $v
                foreach ($v as $key => $value) {
                    // Initialize $k to 1 for each iteration
                    $k = 1;

                    // Loop through each element in the $data array
                    foreach ($data as $valu) {
                        // Check if the value at index 3 in $valu matches $key,
                        // $k is 1, and $ke matches the value at index 0 in $valu
                        if ($valu[3] === $key && $k == 1 && $ke === $valu[0]) {
                            // If conditions are met, append an array to $val
                            $val[] = [
                                $valu[0],               // Index 0 of $valu
                                $valu[1],               // Index 1 of $valu
                                $valu[2],               // Index 2 of $valu
                                $valu[3],               // Index 3 of $valu
                                $valu[4],               // Index 4 of $valu
                                $value,                 // Value from the inner loop
                                $value * $valu[6],      // Calculation based on value from inner loop and index 6 of $valu
                            ];

                            // Increment $k to avoid appending multiple times
                            $k++;
                        }
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
        'Ironmongery Set Name',
        'Category',
        'Code',
        'Name',
        'Supplier',
        'Quantity',
        'Price',
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
                $headerRange1 = 'A1:G1';
                $headerRange2 = 'A2:G2';
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
                foreach (range('A', 'G') as $columnID) {
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
                'G' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'G' => $currencyFormats['£'],
            ];
        } else {
            return [
                'G' => $currencyFormats['€'],
            ];
        }

    }
}
