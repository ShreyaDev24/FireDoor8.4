<?php

namespace App\Support;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SelectedOptionExportColumns
{
    public const RECORD_ID = '_record_id';

    public const MODULE = '_module';

    public const LEAF_MAP = '_leaf_map';

    /**
     * Append hidden identifier columns after the visible export columns.
     * Leaf ids are stored for Door Dimension Custom, where several price
     * columns can share the same visible name.
     *
     * @param  array<int, int|string>  $recordIds  Master-record ids in sheet row order.
     * @param  array<int, int>  $leafIds  Leaf-type ids in visible cost-column order.
     */
    public static function append(Worksheet $sheet, array $recordIds, string $module, array $leafIds = []): void
    {
        $highestIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $leafMap = [];

        if ($leafIds !== []) {
            $firstLeaf = $highestIndex - count($leafIds) + 1;

            foreach (array_values($leafIds) as $offset => $leafId) {
                $letter = Coordinate::stringFromColumnIndex($firstLeaf + $offset);
                $leafMap[$letter] = (int) $leafId;

                $comment = $sheet->getComment($letter . '1');
                $comment->getText()->createTextRun((string) (int) $leafId);
                $comment->setAuthor('FireDoor');
                $comment->setWidth('80px');
                $comment->setHeight('20px');
            }
        }

        $columns = [
            self::RECORD_ID => null,
            self::MODULE => null,
        ];

        if ($leafMap !== []) {
            $columns[self::LEAF_MAP] = json_encode($leafMap);
        }

        $letters = [];
        $columnIndex = $highestIndex;

        foreach ($columns as $header => $value) {
            $columnIndex++;
            $letter = Coordinate::stringFromColumnIndex($columnIndex);
            $letters[$header] = $letter;

            $sheet->setCellValueExplicit($letter . '1', $header, DataType::TYPE_STRING);
            $sheet->getColumnDimension($letter)->setVisible(false);
            $sheet->getColumnDimension($letter)->setWidth(14);

            if ($header === self::LEAF_MAP && $value !== null) {
                $comment = $sheet->getComment($letter . '1');
                $comment->getText()->createTextRun((string) $value);
                $comment->setAuthor('FireDoor');
            }

            if ($value !== null) {
                // The map is repeated on every data row so a filtered sheet still imports.
                $columns[$header] = $value;
            }
        }

        $row = 2;

        foreach ($recordIds as $recordId) {
            $sheet->setCellValueExplicit(
                $letters[self::RECORD_ID] . $row,
                (string) $recordId,
                DataType::TYPE_STRING
            );
            $sheet->setCellValueExplicit(
                $letters[self::MODULE] . $row,
                $module,
                DataType::TYPE_STRING
            );

            if (isset($letters[self::LEAF_MAP])) {
                $sheet->setCellValueExplicit(
                    $letters[self::LEAF_MAP] . $row,
                    (string) $columns[self::LEAF_MAP],
                    DataType::TYPE_STRING
                );
            }

            $row++;
        }
    }
}
