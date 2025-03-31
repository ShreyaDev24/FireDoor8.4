<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\ConfigurableItems;
use App\Models\IronmongeryInfoModel;
use App\Models\SettingCurrency;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\Company;
use Auth;

class IronmongeryInfoExport implements FromCollection,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        // if (Auth::user()->UserType == 2) {
        //     $UserId = CompanyUsers();
        // }else{
            $user = Auth::user();
            $UserId = [$user->id];
        // }

        $IronmongeryInfo = IronmongeryInfoModel::whereIn('UserId', $UserId )->orderBy('Category','asc')->orderBy('Name','asc')->get();

        $j = 1;
        $data = [];
        foreach($IronmongeryInfo as $value){


            $id = $value->id;
            $FireRating = $value->FireRating;
            $Category = $value->Category;
            $Name = $value->Name;
            $Code = $value->Code;
            // $Dimensions = $value->Dimensions;
            $Description = $value->Description;
            $Supplier = $value->Supplier;
            $Price = $value->Price;

            $data[] = array(
                $j,
                $id,
                $FireRating,
                $Category,
                $Name,
                $Code,
                // $Dimensions,
                $Description,
                $Supplier,
                $Price
            );
            $j++;
        }

        $allData = [$data];

        return collect($allData);
    }
    public function headings(): array
    {
        $a = [
            'S. No.',
            'Id',
            'FireRating',
            'Category',
            'Name',
            'Code',
            // 'Dimensions',
            'Description',
            'Supplier',
            'Price'
        ];

        $d = [$a];
        return $d;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:J1'; // All headers
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
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],

                ];
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
