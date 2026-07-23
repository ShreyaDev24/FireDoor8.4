<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LeafSetBespoke implements FromCollection, WithHeadings, WithEvents, WithTitle, WithColumnFormatting
{
    private const VICAIMA_CONFIG_IDS = [4, 5, 6, 9];

    private const STANDARD_CONFIG_IDS = [1, 2, 7, 8];

    public function __construct(
        protected $id,
        protected $vid,
        protected $result
    ) {
    }

    public function collection()
    {
        $vicaimaRows = [];
        $standardRows = [];
        $vicaimaTotalCost = 0;
        $vicaimaGTSell = 0;
        $standardTotalCost = 0;
        $standardGTSell = 0;
        $marginMarkup = 'Margin';

        foreach ($this->result['data'] as $value) {
            if ($value->Category !== 'LeafSetBesPoke') {
                continue;
            }

            $marginMarkup = $value->MarginMarkup;
            $configItem = (int) $value->configurableitems;
            $rowCells = $this->mapDescriptionToRowCells($value);

            if (in_array($configItem, self::VICAIMA_CONFIG_IDS, true)) {
                $vicaimaRows[] = $rowCells;
                $vicaimaTotalCost += $value->TotalCost;
                $vicaimaGTSell += $value->GTSellPrice;
            } elseif (in_array($configItem, self::STANDARD_CONFIG_IDS, true)) {
                $standardRows[] = $rowCells;
                $standardTotalCost += $value->TotalCost;
                $standardGTSell += $value->GTSellPrice;
            }
        }

        $sheetRows = [];

        if ($vicaimaRows !== []) {
            $sheetRows[] = $this->titleRow('Door Details');
            $sheetRows[] = $this->vicaimaHeaderRow($marginMarkup);
            $serial = 1;
            foreach ($vicaimaRows as $cells) {
                $sheetRows[] = array_merge([$serial++], $cells);
            }
            $sheetRows[] = $this->sectionSubtotalRow($vicaimaTotalCost, $vicaimaGTSell);
        }

        if ($vicaimaRows !== [] && $standardRows !== []) {
            $sheetRows[] = array_fill(0, 14, '');
        }

        if ($standardRows !== []) {
            $sheetRows[] = $this->titleRow('Door Details');
            $sheetRows[] = $this->standardHeaderRow($marginMarkup);
            $serial = 1;
            foreach ($standardRows as $cells) {
                $sheetRows[] = array_merge([$serial++], $cells);
            }
            $sheetRows[] = $this->sectionSubtotalRow($standardTotalCost, $standardGTSell);
        }

        $grandTotalCost = $vicaimaTotalCost + $standardTotalCost;
        $grandGTSell = $vicaimaGTSell + $standardGTSell;

        if ($vicaimaRows !== [] || $standardRows !== []) {
            if ($vicaimaRows !== [] && $standardRows !== []) {
                $sheetRows[] = $this->grandTotalRow($grandTotalCost, $grandGTSell);
            }
        }

        return collect($sheetRows);
    }

    public function headings(): array
    {
        // Full layout is built in collection() to match PDF section order (Vicaima then Standard).
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],
                ];

                $columnHeaderStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],
                ];

                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellA = trim((string) $sheet->getCell('A' . $row)->getValue());
                    if ($cellA === 'Door Details') {
                        $sheet->mergeCells('A' . $row . ':N' . $row);
                        $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray($headerStyle);
                    }

                    $cellG = trim((string) $sheet->getCell('G' . $row)->getValue());
                    if ($cellG === 'Door Dimensions Code') {
                        $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFD9D9D9'],
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF000000'],
                                ],
                            ],
                        ]);
                    }

                    if ($cellA === 'S.No') {
                        $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray($columnHeaderStyle);
                        $sheet->getStyle('A' . $row . ':N' . $row)->getAlignment()->setWrapText(true);
                    }
                }

                foreach (range('A', 'N') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Door Details';
    }

    public function columnFormats(): array
    {
        $currencyFormats = [
            '$' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            '£' => '£#,##0.00',
            '€' => '€#,##0.00',
        ];

        $currency = $this->result['currency'];

        if ($currency == '$') {
            return [
                'J' => $currencyFormats['$'],
                'K' => $currencyFormats['$'],
                'L' => $currencyFormats['$'],
                'M' => $currencyFormats['$'],
            ];
        }

        if ($currency == '£') {
            return [
                'J' => $currencyFormats['£'],
                'K' => $currencyFormats['£'],
                'L' => $currencyFormats['£'],
                'M' => $currencyFormats['£'],
            ];
        }

        return [
            'J' => $currencyFormats['€'],
            'K' => $currencyFormats['€'],
            'L' => $currencyFormats['€'],
            'M' => $currencyFormats['€'],
        ];
    }

    /**
     * @return array<int, mixed>
     */
    private function mapDescriptionToRowCells(object $value): array
    {
        $words = explode('|', (string) $value->Description);

        return [
            $words[0] ?? '',
            $words[1] ?? '',
            $words[2] ?? '',
            $words[3] ?? '',
            $words[4] ?? '',
            $words[5] ?? '',
            $value->QuantityOfDoorTypes,
            $value->Unit,
            $value->UnitCost,
            round($value->TotalCost, 2),
            $value->UnitPriceSell,
            $value->GTSellPrice,
            $value->Margin . '%',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function titleRow(string $title): array
    {
        return array_merge([$title], array_fill(0, 13, ''));
    }

    /**
     * @return array<int, string>
     */
    private function vicaimaHeaderRow(string $marginMarkup): array
    {
        return [
            'S.No',
            'Door Type',
            'Door Core',
            'Lipping Type',
            'Lipping Thickness/Lipping Species',
            'Door Leaf Size',
            'Door Dimensions Code',
            'Total Quantity',
            'Unit',
            'Unit Cost',
            'Total Cost',
            'Unit Price Sell ',
            'GT Sell Price',
            $marginMarkup,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function standardHeaderRow(string $marginMarkup): array
    {
        return [
            'S.No',
            'Door Type',
            'Door Core',
            'Lipping Type',
            'Lipping Thickness',
            'Lipping Species',
            'Door Leaf Size',
            'Total Quantity',
            'Unit',
            'Unit Cost',
            'Total Cost',
            'Unit Price Sell ',
            'GT Sell Price',
            $marginMarkup,
        ];
    }

    /**
     * @return array<int, mixed>
     */
    private function sectionSubtotalRow(float $totalCost, float $gtSell): array
    {
        return [
            '', '', '', '', '', '', '', '', '', '',
            round($totalCost, 2),
            '',
            round($gtSell, 2),
            '',
        ];
    }

    /**
     * @return array<int, mixed>
     */
    private function grandTotalRow(float $totalCost, float $gtSell): array
    {
        return [
            '', '', '', '', '', '', '', '', '', '',
            round($totalCost, 2),
            '',
            round($gtSell, 2),
            '',
        ];
    }
}
