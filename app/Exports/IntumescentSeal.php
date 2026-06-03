<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\SettingIntumescentSeals2;

class IntumescentSeal implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $id, $vid, $result;

    function __construct($id, $vid, $result)
    {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    public function collection()
    {
        $items = Item::join('item_master', 'items.itemId', 'item_master.itemID')
            ->leftJoin('lipping_species', 'lipping_species.id', 'items.LippingSpecies')
            ->where('items.QuotationId', $this->id)
            ->where('items.VersionId', $this->vid)
            ->whereNotNull('items.IntumescentLeapingSealType')
            ->where('items.IntumescentLeapingSealType', '!=', '')
            ->select('item_master.*', 'items.*', 'lipping_species.SpeciesName')
            ->orderBy('items.itemId', 'ASC')
            ->get();

        $data = [];

        foreach ($items as $value) {
            $arrangementLabel = '';
            if (!empty($value->IntumescentLeapingSealArrangement)) {
                $intum = SettingIntumescentSeals2::select('brand', 'intumescentSeals')
                    ->find($value->IntumescentLeapingSealArrangement);
                if ($intum) {
                    $arrangementLabel = trim($intum->brand . ' ' . $intum->intumescentSeals);
                }
            }

            $data[] = [
                $value->DoorType ? str_replace('_', ' ', $value->DoorType) : '',
                $value->IntumescentLeapingSealType ?? '',
                $value->IntumescentLeapingSealLocation ?? '',
                $value->IntumescentLeapingSealColor ?? '',
                $arrangementLabel,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        $titleRow = ['Intumescent Seal'];
        $headerRow = [
            'Door Type',
            'Intumescent Seal Type',
            'Intumescent Seal Location',
            'Intumescent Seal Colour',
            'Intumescent Seal Arrangement',
        ];

        return [$titleRow, $headerRow];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                foreach (range('A', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $cellRange1 = 'A1:E1';
                $cellRange2 = 'A2:E2';
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
            },
        ];
    }

    public function title(): string
    {
        return 'Intumescent Seal';
    }
}
