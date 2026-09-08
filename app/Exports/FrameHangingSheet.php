<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;

class FrameHangingSheet implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    protected $id,$vid,$result,$section;
    function __construct($id,$vid) {
        $this->id = $id;
        $this->vid = $vid;
    }

    public function collection()
    {
        $item = Item::Join('item_master','items.itemId','item_master.itemID')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->select('item_master.doorNumber','items.SOHeight', 'items.SOWidth', 'items.SOWallThick', 'items.DoorType', 'items.DoorLeafFinish', 'items.DoorLeafFacing', 'items.LippingType', 'items.LippingSpecies', 'items.LeafWidth1', 'items.LeafWidth2', 'items.LeafHeight', 'items.FrameThickness', 'items.Undercut', 'items.Handing','items.FireRating')->orderBy('items.itemId','ASC')->get();
        $data = [];
        $lineNo = 1;
        foreach($item as $value){

            $data[] = array(
                $lineNo++,
                $value->doorNumber,
                $value->SOHeight,
                $value->SOWidth,
                $value->SOWallThick,
                $value->DoorType,
                $value->DoorLeafFinish,
                $value->DoorLeafFacing,
                $value->LippingType.'-'.$value->LippingSpecies,
                $value->LeafWidth1,
                $value->LeafWidth2 ?? 0,
                $value->LeafHeight,
                $value->FrameThickness,
                $value->Undercut,
                $value->Handing,
                $value->FireRating,
                '',
                '',
            );
        }
        $footData = [
            '','','','','','','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);

    }

    public function headings(): array
    {
        $titleRow = ['Worksheet Frame Hanging Sheet'];
        $headerRow = [
            'Line No.',
            'Door No.',
            'S.O Height',
            'S.O Width',
            'S.O Wall Thick',
            'Door Type',
            'Door Leaf Finish',
            'Door Leaf Facing',
            'Lipping Type - LippingSpecies',
            'Leaf Width 1',
            'Leaf Width 2',
            'Leaf Height',
            'Leaf Thick',
            'Undercut',
            'Handing',
            'Fire Rating',
            'Assembley',
            'Pallett Number',
        ];

        return [$titleRow, $headerRow];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                foreach (range('A', 'R') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $cellRange1 = 'A1:R1';
                $cellRange2 = 'A2:R2';
                $styleArray = [
                    'font' => [
                        'bold' => true,
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
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray);
                $event->sheet->getStyle($cellRange2)->getAlignment()->setWrapText(true);

                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(30);

                // getStyle() leaves its range as the sheet's active selection; reset it to A1
                // so the whole header row isn't stored as "selected" when the file is saved
                // (that's what caused header cells to render blank until switching tabs).
                $sheet->getDelegate()->setSelectedCells('A1');
            },
        ];
    }

    public function title(): string
    {
        return 'Frame Hanging Sheet';
    }
}
