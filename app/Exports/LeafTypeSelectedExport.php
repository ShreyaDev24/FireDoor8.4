<?php

namespace App\Exports;

use App\Models\LeafType;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeafTypeSelectedExport implements FromQuery, WithHeadings, WithStyles
{
    protected array $ids;
    protected int $userId;

    public function __construct(array $ids, int $userId)
    {
        $this->ids = $ids;
        $this->userId = $userId;
    }

    public function query()
    {
        return LeafType::leftJoin('selected_leaf_type as slt', function ($join) {
                $join->on('leaf_type.id', '=', 'slt.leaf_id')
                    ->where('slt.editBy', $this->userId);
            })
            ->whereIn('leaf_type.id', $this->ids)
            ->select([
                'leaf_type.LeafType as leaf_type',
                'leaf_type.VicaimaDoorCore as vicaima',
                'leaf_type.Seadec as seadec',
                'leaf_type.Deanta as deanta',
                'leaf_type.MMM as mmm',
                \DB::raw('COALESCE(slt.selectedPrice, 0) as price_per_m2'),
            ])
            ->orderBy('leaf_type.LeafType');
    }

    public function headings(): array
    {
        return [
            'Leaf Type',
            'Vicaima',
            'Seadec',
            'Deanta',
            'MMM',
            'Price Per m²',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
        ];
    }
}
