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
                $rows[] = $this->applyBreakdownTotals($section, $rowIndex, $dataRow);

                foreach ($this->breakdownRowSpecs($section, $rowIndex, $dataRow) as $spec) {
                    $rows[] = $spec['cells'];
                }
            }

            $rows[] = ['']; // Blank row for spacing
        }

        return $rows;
    }

    /**
     * Client rule (2026-09-02): the Door Details row's own Unit Cost/Total Cost/Unit Price
     * Sell/GT Sell Price should be whatever the breakdown block underneath adds up to (core +
     * facing + lipping + finish), with the row's existing margin applied on top — NOT the value
     * LeafSetBesPoke() saved. This only changes what this one sheet DISPLAYS; it's a display-layer
     * override applied to a copy of the row array, and never writes back to bom_calculations, so
     * LeafSetBesPoke() and every other consumer of that data are completely unaffected.
     */
    private function applyBreakdownTotals(array $section, int $rowIndex, array $dataRow): array
    {
        $leaves = $section['breakdown'][$rowIndex] ?? [];
        if (empty($leaves)) {
            return $dataRow;
        }

        $unitCost = round(array_sum(array_map(fn ($leaf) => $this->leafTotal($leaf), $leaves)), 2);
        $qty = is_numeric($dataRow[8] ?? null) ? (float) $dataRow[8] : 1.0;
        $totalCost = round($unitCost * $qty, 2);
        $marginPercent = $this->marginPercent($dataRow[14] ?? null);

        $dataRow[10] = $unitCost;
        $dataRow[11] = $totalCost;
        $dataRow[12] = $this->sellPrice($unitCost, $marginPercent);
        $dataRow[13] = $this->sellPrice($totalCost, $marginPercent);

        return $dataRow;
    }

    /**
     * One leaf's core + facing (or matched Laminate sheet) + lipping + finish cost, added together
     * — the same figure LeafSetBesPoke() would show as "Total Door Leaf Cost" before margin.
     */
    private function leafTotal(array $leaf): float
    {
        // totalLeafCost is already computed once, authoritatively, in buildLeafSetBreakdownEntry()
        // (common_helper.php) as coreCost + facingTotal + lippingTotal + finishTotal — including
        // the edge case where a Laminate leaf has BOTH a non-zero generic facing rate AND a matched
        // sheet price (finishTotal). Re-deriving it here from facingTotal/finishTotal separately
        // would double-count that case, so this reads the single stored value instead.
        return (float) ($leaf['totalLeafCost'] ?? 0);
    }

    private function marginPercent($rawMargin): float
    {
        $numeric = (float) rtrim(trim((string) $rawMargin), '%');

        return $numeric >= 0 && $numeric < 100 ? $numeric : 0.0;
    }

    private function sellPrice(float $cost, float $marginPercent): float
    {
        return $marginPercent > 0 ? round($cost / (1 - $marginPercent / 100), 2) : $cost;
    }

    /**
     * The detailed "show your work" breakdown block under a Door Details row — core slab size,
     * facing m2 x rate, lipping cross-section x rate, finish m2 x rate — one header+value row pair
     * per component. Client rule (2026-09-02): keep this detailed layout exactly as it was, just
     * with no colour fill (bold headers only). Returns [] for every other section/row.
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

            // Total/Cost-Per-Core value always lands in column H, whatever component this is —
            // Lipping needs 4 detail columns (D-G) before its total, the others only 2, so pinning
            // every component's own final cost to the same column keeps them vertically aligned
            // instead of landing in F for some rows and H for others.
            $specs[] = $this->row(['C' => $coreName . ' Core Blank' . $suffix, 'D' => 'Door Core Size', 'E' => 'Quantity', 'H' => 'Cost Per Core'], true);
            $specs[] = $this->row(['D' => $leaf['coreSizeCode'] ?? '', 'E' => $leaf['coreQty'] ?? '', 'H' => $leaf['coreCost'] ?? ''], false);

            $sheetOptions = $leaf['laminateSheetOptions'] ?? [];
            if (!empty($sheetOptions)) {
                // Laminate with registered sheet sizes: list every candidate size (smallest first),
                // bolding the one actually picked instead of colour-highlighting it.
                $specs[] = $this->row(['C' => $facingType, 'D' => 'Sheet Sizes', 'E' => 'Cost per sheet', 'F' => 'Quantity', 'H' => 'Total Cost'], true);
                foreach ($sheetOptions as $option) {
                    $isSelected = !empty($option['isSelected']);
                    $specs[] = $this->row([
                        'D' => $option['sizeLabel'] ?? '',
                        'E' => $option['costPerSheet'] ?? '',
                        'F' => $option['qty'] ?? '',
                        'H' => $isSelected ? ($option['total'] ?? '') : '',
                    ], $isSelected);
                }
            } else {
                $specs[] = $this->row(['C' => $facingType . ' (m²)', 'D' => 'M² area of ' . strtolower($facingType), 'E' => 'Cost per M²', 'H' => 'Total Cost'], true);
                $specs[] = $this->row(['D' => $leaf['facingM2'] ?? '', 'E' => $leaf['facingCostPerM2'] ?? '', 'H' => $leaf['facingTotal'] ?? ''], false);
            }

            $specs[] = $this->row(['C' => 'Lipping', 'D' => 'LM of Lipping used', 'E' => 'Lipping Cross-section (m²/LM)', 'F' => 'Cost per M³', 'G' => 'Cost per LM', 'H' => 'Total Cost'], true);
            $specs[] = $this->row(['D' => $leaf['lippingLM'] ?? '', 'E' => $leaf['lippingCrossSectionM2PerLM'] ?? '', 'F' => $leaf['lippingCostPerM3'] ?? '', 'G' => $leaf['lippingCostPerLM'] ?? '', 'H' => $leaf['lippingTotal'] ?? ''], false);

            // A matched Laminate sheet already carries its cost above — no separate finish/paint
            // step applies to it, so skip this section entirely in that case.
            if (empty($sheetOptions)) {
                $specs[] = $this->row(['C' => 'Prime / Paint / Lacquer', 'D' => 'M² area of finishing', 'E' => 'Cost per M² to finish', 'H' => 'Total Cost'], true);
                // finishSteps: usually one line (the selected finish), but a Kraft Paper door
                // finished "Painted" carries two — Primed then Painted — each its own row. Older
                // saved rows from before this existed only have the flat finishM2/finishCostPerM2/
                // finishTotal fields; fall back to a single-step row from those.
                $steps = $leaf['finishSteps'] ?? [['label' => '', 'm2' => $leaf['finishM2'] ?? '', 'costPerM2' => $leaf['finishCostPerM2'] ?? '', 'total' => $leaf['finishTotal'] ?? '']];
                foreach ($steps as $step) {
                    $specs[] = $this->row(['C' => $step['label'] ?? '', 'D' => $step['m2'] ?? '', 'E' => $step['costPerM2'] ?? '', 'H' => $step['total'] ?? ''], false);
                }
            }
        }

        return $specs;
    }

    private function row(array $cellsByColumn, bool $bold): array
    {
        $cells = array_fill(0, self::SHEET_COLUMN_COUNT, '');
        foreach ($cellsByColumn as $column => $value) {
            $cells[\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($column) - 1] = $value;
        }

        return ['cells' => $cells, 'bold' => $bold];
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

                    // Bold the total line of the breakdown block sitting under each Door Details
                    // data row (no colour — plain, per the client's request).
                    // $currentRow is the row number of the data row at the top of each iteration.
                    foreach ($section['data'] as $rowIndex => $dataRow) {
                        foreach ($this->breakdownRowSpecs($section, $rowIndex, $dataRow) as $spec) {
                            $currentRow++; // the next breakdown row, directly under the data/previous row

                            if ($spec['bold']) {
                                $event->sheet->getStyle(sprintf('A%s:S%s', $currentRow, $currentRow))->applyFromArray([
                                    'font' => ['bold' => true],
                                ]);
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
