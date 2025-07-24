<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\LippingSpecies;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\NonConfigurableItems;
use Auth;

class NonConfig implements FromCollection,WithHeadings,WithEvents
{

    public function collection()
    {
        $uIds = CompanyUsers();
        $nonConfigData = NonConfigurableItems::wherein('userId',$uIds)->where('status', 1)->get();

        $j = 1;
        $i = 0;
        $data = [];
        foreach($nonConfigData as $item){

            $name = $item->name;
            $product_code = $item->product_code;
            $description = $item->description;
            $unit = $item->unit;
            $quantity = $item->quantity;
            $storePrice = $item->price;

            $data[] = [
                $j,
                $name,
                $product_code,
                $description,
                $unit,
                $storePrice,
            ];
            $i++;
            $j++;
        }

        $footData = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'S.No',
            'Name',
            'Product Code',
            'Description ',
            'Unit ',
            'Price ',
        ];

        $d = [$a];
        return $d;
    }

    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange = 'A1:F1'; // All headers
                // $cellRange->setFontWeight('bold');
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color'=> '#000000'
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],

                ];
                $event->sheet->getStyle("A1:F1")->getAlignment()->setTextRotation(90)->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(10)->setRowHeight(60);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
