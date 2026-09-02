<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use App\Models\Quotation;

// WithStrictNullComparison matters here: PhpSpreadsheet's Worksheet::fromArray() (which Maatwebsite
// calls under FromArray) defaults to loose (==) comparison against null when deciding which cells to
// skip — and in PHP, 0 == null is true. Without this interface every legitimate 0 in a row (a Primed
// rate that's genuinely £0, a Laminate facing rate that isn't configured, £0.00 costs) gets silently
// left blank instead of written as "0".
class DoorTypeSheet implements FromArray,WithEvents,WithTitle,WithColumnFormatting,WithColumnWidths,WithStrictNullComparison
{
    const SHEET_COLUMN_COUNT = 19; // A..S, matches the widest section heading (Door Details)

    const FILL_CORE = 'BDD7EE';    // blue
    const FILL_FACING = 'FFE699';  // yellow
    const FILL_LIPPING = 'C6E0B4'; // green
    const FILL_FINISH = 'E2EFDA';  // light green
    const FILL_TOTAL = 'F8CBAD';   // orange

    public function __construct(protected $sections, protected $DoorType, protected $QId)
    {
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $rows = [];
        foreach ($this->sections as $section) {
            $rows[] = [$section['title']];  // Section title
            $rows[] = $section['headings']; // Section headings
            foreach ($section['data'] as $rowIndex => $dataRow) {
                $rows[] = $dataRow; // Section content

                foreach ($this->breakdownRowSpecs($section, $rowIndex, $dataRow) as $spec) {
                    $rows[] = $spec['cells'];
                }
            }

            $rows[] = ['']; // Blank row for spacing
        }

        return $rows;
    }

    /**
     * Builds the colour-coded "show your work" block (core slab, facing m2, lipping, finish) that
     * goes directly under a Door Details row, when that row has a saved Breakdown. Returns [] for
     * every other section/row, so nothing else in the sheet is affected.
     */
    private function breakdownRowSpecs(array $section, int $rowIndex, array $dataRow): array
    {
        $leaves = $section['breakdown'][$rowIndex] ?? [];
        if (empty($leaves)) {
            return [];
        }

        $coreName = trim((string) ($dataRow[2] ?? '')) ?: 'Core';
        $specs = [];
        $leafCount = count($leaves);

        foreach ($leaves as $leafNumber => $leaf) {
            $suffix = $leafCount > 1 ? ' (Leaf ' . ($leafNumber + 1) . ')' : '';
            $facingType = str_replace('_', ' ', (string) ($leaf['facingType'] ?? 'Facing'));

            $specs[] = $this->row(['C' => $coreName . ' Core' . $suffix, 'D' => 'Door Core Size', 'E' => 'Quantity', 'F' => 'Cost Per Core'], self::FILL_CORE, true);
            $specs[] = $this->row(['D' => $leaf['coreSizeCode'] ?? '', 'E' => $leaf['coreQty'] ?? '', 'F' => $leaf['coreCost'] ?? ''], self::FILL_CORE, false);

            $sheetOptions = $leaf['laminateSheetOptions'] ?? [];
            if (!empty($sheetOptions)) {
                // Laminate with registered sheet sizes: list every candidate size (smallest first),
                // highlighting the one actually picked — matches the client's own reference sheet.
                $specs[] = $this->row(['C' => $facingType, 'D' => 'Sheet Sizes', 'E' => 'Cost per sheet', 'F' => 'Quantity', 'G' => 'Total Cost'], self::FILL_FACING, true);
                foreach ($sheetOptions as $option) {
                    $isSelected = !empty($option['isSelected']);
                    $specs[] = $this->row([
                        'D' => $option['sizeLabel'] ?? '',
                        'E' => $option['costPerSheet'] ?? '',
                        'F' => $option['qty'] ?? '',
                        'G' => $isSelected ? ($option['total'] ?? '') : '',
                    ], $isSelected ? self::FILL_TOTAL : self::FILL_FACING, $isSelected);
                }
            } else {
                $specs[] = $this->row(['C' => $facingType . ' (m²)', 'D' => 'M² area of ' . strtolower($facingType), 'E' => 'Cost per M²', 'F' => 'Total Cost'], self::FILL_FACING, true);
                $specs[] = $this->row(['D' => $leaf['facingM2'] ?? '', 'E' => $leaf['facingCostPerM2'] ?? '', 'F' => $leaf['facingTotal'] ?? ''], self::FILL_FACING, false);
            }

            $specs[] = $this->row(['C' => 'Lipping', 'D' => 'LM of Lipping used', 'E' => 'Lipping Cross-section (m²/LM)', 'F' => 'Cost per M³', 'G' => 'Cost per LM', 'H' => 'Total Cost'], self::FILL_LIPPING, true);
            $specs[] = $this->row(['D' => $leaf['lippingLM'] ?? '', 'E' => $leaf['lippingCrossSectionM2PerLM'] ?? '', 'F' => $leaf['lippingCostPerM3'] ?? '', 'G' => $leaf['lippingCostPerLM'] ?? '', 'H' => $leaf['lippingTotal'] ?? ''], self::FILL_LIPPING, false);

            // A matched Laminate sheet already shows its price in the table above — no separate
            // finish/paint step applies to it, so skip this section entirely in that case.
            if (empty($sheetOptions)) {
                $specs[] = $this->row(['C' => 'Prime / Paint / Lacquer', 'D' => 'M² area of finishing', 'E' => 'Cost per M² to finish', 'F' => 'Total Cost'], self::FILL_FINISH, true);
                // finishSteps: usually one line (the selected finish), but a Kraft Paper door finished
                // "Painted" carries two — Primed then Painted — each its own row. Older saved rows from
                // before this existed only have the flat finishM2/finishCostPerM2/finishTotal fields;
                // fall back to a single-step row from those so they still render correctly.
                $steps = $leaf['finishSteps'] ?? [['label' => '', 'm2' => $leaf['finishM2'] ?? '', 'costPerM2' => $leaf['finishCostPerM2'] ?? '', 'total' => $leaf['finishTotal'] ?? '']];
                foreach ($steps as $step) {
                    $specs[] = $this->row(['C' => $step['label'] ?? '', 'D' => $step['m2'] ?? '', 'E' => $step['costPerM2'] ?? '', 'F' => $step['total'] ?? ''], self::FILL_FINISH, false);
                }
            }
        }

        return $specs;
    }

    private function row(array $cellsByColumn, string $fill, bool $bold): array
    {
        $cells = array_fill(0, self::SHEET_COLUMN_COUNT, '');
        foreach ($cellsByColumn as $column => $value) {
            $cells[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($column) - 1] = $value;
        }

        return ['cells' => $cells, 'fill' => $fill, 'bold' => $bold];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, // Set width for column A
            'B' => 15, // Set width for column B
            'C' => 30, // Set width for column C
            'D' => 30, // Set width for column C
            'E' => 30, // Set width for column C
            'F' => 30, // Set width for column C
            'G' => 30, // Set width for column C
            'H' => 15, // Set width for column C
            'I' => 15, // Set width for column C
            'J' => 15, // Set width for column C
            'K' => 15, // Set width for column C
            'L' => 15, // Set width for column C
            'M' => 15, // Set width for column C
            'N' => 15, // Set width for column C
            'O' => 15, // Set width for column C
            'P' => 15, // Set width for column C
            'Q' => 15, // Set width for column C
            'R' => 15, // Set width for column C
            'S' => 15, // Set width for column C
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $currentRow = 1;
                foreach ($this->sections as $section) {
                    // Bold the title
                    $event->sheet->mergeCells(sprintf('A%s:S%s', $currentRow, $currentRow));
                    $event->sheet->getStyle('A' . $currentRow)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 14,
                        ],
                    ]);

                    // Move to the row where the headings are located
                    $currentRow++;

                    // Bold the headings
                    $event->sheet->getStyle(sprintf('A%s:S%s', $currentRow, $currentRow))->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);

                    // if($section['title'] == "Glass"){
                    //     // Merge specific columns for headings, for example, D, E, F
                    //     $event->sheet->mergeCells("D{$currentRow}:G{$currentRow}");
                    // }

                    $currentRow++; // move pointer to the first data row

                    // Colour the breakdown block(s) sitting under each Door Details data row.
                    // $currentRow is the row number of the data row at the top of each iteration.
                    foreach ($section['data'] as $rowIndex => $dataRow) {
                        foreach ($this->breakdownRowSpecs($section, $rowIndex, $dataRow) as $spec) {
                            $currentRow++; // the next breakdown row, directly under the data/previous row

                            $style = $event->sheet->getStyle(sprintf('A%s:S%s', $currentRow, $currentRow));
                            $style->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => $spec['fill']],
                                ],
                            ]);
                            if ($spec['bold']) {
                                $style->applyFromArray(['font' => ['bold' => true]]);
                            }
                        }

                        $currentRow++; // advance past this data row's block to the next data row
                    }

                    $currentRow++; // blank spacer row
                }
            },
        ];
    }

    public function title(): string
    {
        return $this->DoorType;
    }

    public function columnFormats(): array
    {
        $currencyFormats = [
            '$' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            '£' => '£#,##0.00',
            '€' => '€#,##0.00'
        ];

        $quotation = Quotation::find($this->QId);
        $currency = QuotationCurrency($quotation->Currency);

        // Apply the appropriate format based on the currency
        $format = $currencyFormats[$currency] ?? NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE;

        if ($currency === '$') {
            return [
                'J' => $currencyFormats['$'],
                'K' => $currencyFormats['$'],
                'L' => $currencyFormats['$'],
                'M' => $currencyFormats['$'],
                'N' => $currencyFormats['$'],
            ];
        } elseif ($currency === '£') {
            return [
                'J' => $currencyFormats['£'],
                'K' => $currencyFormats['£'],
                'L' => $currencyFormats['£'],
                'M' => $currencyFormats['£'],
                'N' => $currencyFormats['£'],
            ];
        } else {
            return [
                'J' => $currencyFormats['€'],
                'K' => $currencyFormats['€'],
                'L' => $currencyFormats['€'],
                'M' => $currencyFormats['€'],
                'N' => $currencyFormats['€'],
            ];
        }
    }
}
