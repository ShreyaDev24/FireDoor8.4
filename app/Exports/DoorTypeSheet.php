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
use App\Models\Quotation;

class DoorTypeSheet implements FromArray,WithEvents,WithTitle,WithColumnFormatting,WithColumnWidths
{
    protected $sections;
    protected $DoorType;
    protected $QId;

    public function __construct($sections,$DoorType,$QId)
    {
        $this->sections = $sections;
        $this->DoorType = $DoorType;
        $this->QId = $QId;
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
            foreach ($section['data'] as $dataRow) {
                $rows[] = $dataRow; // Section content
            }
            $rows[] = ['']; // Blank row for spacing
        }
        return $rows;
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $currentRow = 1;
                foreach ($this->sections as $section) {
                    // Bold the title
                    $event->sheet->mergeCells("A{$currentRow}:O{$currentRow}");
                    $event->sheet->getStyle("A{$currentRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 14,
                        ],
                    ]);

                    // Move to the row where the headings are located
                    $currentRow++;

                    // Bold the headings
                    $event->sheet->getStyle("A{$currentRow}:O{$currentRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);

                    // if($section['title'] == "Glass"){
                    //     // Merge specific columns for headings, for example, D, E, F
                    //     $event->sheet->mergeCells("D{$currentRow}:G{$currentRow}");
                    // }

                    // Increment the row by the number of rows in each section
                    $currentRow += count($section['data']) + 2; // +2 for the title and headings
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

        if ($currency == '$') {
            return [
                'J' => $currencyFormats['$'],
                'K' => $currencyFormats['$'],
                'L' => $currencyFormats['$'],
                'M' => $currencyFormats['$'],
                'N' => $currencyFormats['$'],
            ];
        } elseif ($currency == '£') {
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

