<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\Option;
use App\Models\LippingSpecies;

class WorksheetHangingDocument implements FromCollection, WithHeadings, WithEvents, WithTitle
{
    protected $id, $vid;

    function __construct($id, $vid)
    {
        $this->id = $id;
        $this->vid = $vid;
    }

    public function collection()
    {
        $quotation = Quotation::where('id', $this->id)->first();
        $configurationItem = !empty($quotation->configurableitems) ? $quotation->configurableitems : 1;

        $items = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->where('quotation_version_items.version_id', $this->vid)
            ->where('items.QuotationId', $this->id)
            ->orderBy('items.itemId', 'ASC')
            ->select('item_master.*', 'items.*')
            ->get();

        $data = [];
        $lineNo = 1;

        foreach ($items as $item) {
            $doorLeafFinish = '';
            if (!empty($item->DoorLeafFinish)) {
                $doorLeafFinish = DoorLeafFinish($configurationItem, $item->DoorLeafFinish) ?: $item->DoorLeafFinish;
                if (!empty($item->DoorLeafFinishColor)) {
                    $doorLeafFinish .= ' + ' . $item->DoorLeafFinishColor;
                }
            }

            $doorLeafFacing = '';
            if (!empty($item->DoorLeafFacing)) {
                $doorLeafFacing = DoorLeafFacing($configurationItem, $item->DoorLeafFacing, $item->DoorLeafFacingValue);
            }

            $lippingParts = [];
            if (!empty($item->LippingType)) {
                $selectedLippingType = Option::where('configurableitems', $configurationItem)
                    ->where('OptionSlug', 'lipping_type')
                    ->where('OptionKey', $item->LippingType)
                    ->first();
                $lippingParts[] = $selectedLippingType ? $selectedLippingType->OptionValue : str_replace('_', ' ', $item->LippingType);
            }
            if (!empty($item->LippingSpecies)) {
                $selectedLippingSpecies = LippingSpecies::find($item->LippingSpecies);
                if ($selectedLippingSpecies != null) {
                    $lippingParts[] = $selectedLippingSpecies->SpeciesName;
                }
            }

            $data[] = [
                $lineNo,
                $item->doorNumber,
                $item->SOHeight,
                $item->SOWidth,
                $item->SOWallThick,
                $item->DoorType,
                $doorLeafFinish,
                $doorLeafFacing,
                implode(' - ', $lippingParts),
                $item->LeafWidth1,
                $item->LeafWidth2,
                $item->LeafHeight,
                $item->LeafThickness,
                $item->Undercut,
                $item->Handing,
                $item->FireRating,
                '',
                '',
            ];

            $lineNo++;
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Line No.',
            'Door No.',
            'S.O Height',
            'S.O Width',
            'S.O Wall Thick',
            'Door Type',
            'Door Leaf Finish',
            'Door Leaf Facing',
            'Lipping Type - Lipping Species',
            'Leaf Width 1',
            'Leaf Width 2',
            'Leaf Height',
            'Leaf Thick',
            'Undercut',
            'Handing',
            'Fire Rating',
            'Assembly',
            'Pallet Number',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = max(1, $event->sheet->getHighestRow());
                $headerRange = 'A1:R1';
                $dataRange = 'A1:R' . $lastRow;

                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF92D050'],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $event->sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                $event->sheet->getRowDimension(1)->setRowHeight(30);

                foreach (range('A', 'R') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Worksheet Door Hanging Sheet';
    }
}
