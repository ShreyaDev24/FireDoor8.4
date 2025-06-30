<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\Quotation;

class SideLightGlazingBeads implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    protected $id,$vid,$result;

    function __construct($id,$vid,$result) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    public function collection()
    {
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id', $this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.GlazingBeadSpecies')->where('QuotationId', $this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $k = 1;
        $data = [];
        foreach($item as $value){
            if ($value->SideLight1 == 'Yes' || $value->SideLight2 == 'Yes'){
                $data[] = array(
                    $value->doorNumber,
                    $value->DoorType,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    $value->SideLight1GlazingBeadsThickness,
                    $value->SideLight1GlazingBeadsWidth,
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $value->SL1Width,
                    4,
                    $value->SL1Height,
                    4,
                    $value->SL2Width,
                    4
                );

                $k++;
            }
        }

        $footData = [
            '','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'Door Ref',
            'Door Type',
            'Timber',
            'Profile',
            'Glazing Bead Height',
            'Glazing Bead Depth',
            'Finish on Bead',
            'SL1 W',
            'QTY',
            'SL1 H',
            'QTY',
            'SL2 H',
            'QTY',
        ];


        $b = ['Side Light Glazing Beads'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:M1';
                $cellRange = 'A2:M2';
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
                $columns = range('M', 'O'); // 'O' should be replaced with the last column you need

                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }


                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Side Light Glazing Beads';
    }
}
