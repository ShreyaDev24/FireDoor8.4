<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * One door type = one sheet of door information.
 *
 * Sections are rendered as: title row, heading row, then the rows themselves.
 */
class DoorInfoSheet implements FromArray, WithEvents, WithTitle, WithColumnWidths
{
    public function __construct(protected $sections, protected $DoorType)
    {
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->sections as $section) {
            $rows[] = [$section['title']];
            $rows[] = $section['headings'];

            foreach ($section['data'] as $dataRow) {
                $rows[] = $dataRow;
            }

            $rows[] = [''];
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 38,
            'B' => 42,
            'C' => 20,
            'D' => 22,
            'E' => 20,
            'F' => 20,
            'G' => 16,
            'H' => 30,
        ];
    }

    /**
     * Excel caps a tab name at 31 characters and rejects \ / ? * : [ ].
     * A door type can legitimately contain those, so clean it up here.
     */
    public function title(): string
    {
        $clean = preg_replace('/[\\\\\\/\\?\\*\\:\\[\\]]+/', '-', (string) $this->DoorType);
        $clean = trim(preg_replace('/\s+/', ' ', (string) $clean));

        if ($clean === '') {
            $clean = 'Door';
        }

        return mb_substr($clean, 0, 31);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $currentRow = 1;

                foreach ($this->sections as $section) {
                    // Section title
                    $event->sheet->mergeCells(sprintf('A%s:H%s', $currentRow, $currentRow));
                    $event->sheet->getStyle('A' . $currentRow)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 13],
                    ]);

                    $currentRow++;

                    // Heading row
                    $event->sheet->getStyle(sprintf('A%s:H%s', $currentRow, $currentRow))->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'EFEFEF'],
                        ],
                    ]);

                    $currentRow++;
                    $currentRow += count($section['data']);
                    $currentRow++; // spacer row
                }
            },
        ];
    }
}
