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

class Summary implements FromCollection,WithTitle,WithEvents,WithColumnFormatting
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
        $id = $this->id;
        $vid = $this->vid;

        $result = BOMCAlculationExport($id,$vid);
        $total = $GTSell = 0;

        $j = 1;
        $total=0; $GTSellPrice=0; $Margin = 0;
        foreach($result['data'] as $value){
            if($value->Category != 'Ironmongery&MachiningCosts'){
                $total = $total + $value->TotalCost;
                $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
                $Margin = $Margin + $value->Margin;
            }
        }
        $data = [];
        $data[0] = array(
            'Ref',
            $result['quotation']['QuotationGenerationId'],
            '',
            '',
            '',
            'Project',
            $result['quotation']['projectname'],
            '',
            'Prepared By',
            $result['userName']
        );
        $data[1] = array(
            'Revision',
            $result['data'][0]['VersionId'],
            'Date',
            $result['today'],
            '',
            'Main Contractor',
            $result['quotation']['CstCompanyName'],
            '',
            'Sales Contact',
            $result['quotation']['SalesContact']
        );
        $data[2] = array(
            ''
        );
        $data[3] = array(
            ''
        );
        $data[4] = array(
            'Doorsets',
            $result['totDoorsetType'],
        );
        $data[5] = array(
            'Ironmongery Sets',
            $result['totIronmongerySet'],
        );
        $data[6] = array(
            'Total Cost',
            $total,
        );
        $data[7] = array(
            'Calculated Sale Price',
            $GTSellPrice,
        );
        $data[8] = array(
            'Any Prices OverRidden',
            '0',
        );


        $allData = [$data];

        return collect($allData);
    }

    public function registerEvents(): array
    {


        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:N1';
                $cellRange = 'A1:A9';
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
                ];
                // $event->sheet->mergeCells($cellRange1);
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getStyle('B2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $event->sheet->getDelegate()->getStyle('B5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $event->sheet->getDelegate()->getStyle('B6')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('C2')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('F1:F2')->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('I1:I2')->applyFromArray($styleArray);
                // $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Summary';
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
                'B' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
            return [
                'B' => $currencyFormats['£'],
            ];
        } else {
            return [
                'B' => $currencyFormats['€'],
            ];
        }
    }
}
