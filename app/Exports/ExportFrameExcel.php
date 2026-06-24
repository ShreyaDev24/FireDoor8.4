<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\DoorFrameConstruction;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFrameExcel implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $id, $vid;

    function __construct($id, $vid)
    {
        $this->id = $id;
        $this->vid = $vid;
    }

    public function collection()
    {

        $item = Item::join('item_master', 'items.itemId', 'item_master.itemID')
            ->leftJoin('door_frame_construction', 'items.DoorFrameConstruction', 'door_frame_construction.DoorFrameConstruction')
            ->leftJoin('lipping_species', 'lipping_species.id', 'items.FrameMaterial')
            ->where('QuotationId', $this->id)
            ->where('VersionId', $this->vid)
            ->where('door_frame_construction.UserId', Auth::user()->id)
            ->select('item_master.*', 'items.*', 'lipping_species.SpeciesName', 'door_frame_construction.Width', 'door_frame_construction.Height')
            ->orderBy('items.itemId', 'ASC')
            ->get();

        $data = [];

        foreach ($item as $value) {
            $data[] = [
                doorcorename($value->configurableitems),
                $value->doorNumber,
                $value->plot_ref_no,
                $value->certification_no,
                $value->FrameDepth,
                $value->RebatedWidth,
                $value->RebatedHeight,
                $value->RebatedHeadDepth,
                $value->HeadFrameThickness,
                $value->FireRating,
                $value->FrameHeight,
                $value->FrameWidth,
                IronmongerySetName($value->IronmongeryID),
                $value->Handing,
                $value->Undercut,
                $value->Saddle,
                str_replace('_', ' ', $value->saddleLocation),
                $value->FourSidedFrame,
                1,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        // Two header rows
        return [
            ['Frame Excel'], // Row 1
            [
                'Door Core',
                'Door Number',
                'Plot Number/Ref',
                'IFC/Certifire No/Q mark Plug',
                'Frame Depth',
                'Rebate Width',
                'Rebate Depth',
                'Rebate Head',
                'Head Thickness',
                'Fire Rating',
                'O/A Frame H',
                'O/A Frame W',
                'Ironmongery Ref',
                'Handing',
                'Undercut',
                'Saddle Req',
                'Saddle Location',
                'Bottom- 4 Sided Frame',
                'Count'
            ]
        ];
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:S1';
                $cellRange = 'A2:S2';
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
                $columns = range('S', 'O'); // 'O' should be replaced with the last column you need

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
        return 'Frames Excel';
    }
}

