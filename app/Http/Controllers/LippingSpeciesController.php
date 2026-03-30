<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpeciesItems;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LippingSpeciesController extends Controller
{

    public function index()
    {

        $auth = Auth::user();

        $userIds = $auth->UserType == 2 ? [1, $auth->id] : [1];

        $species = LippingSpecies::with([
            'lipping_species_items' => function ($q) use ($auth) {
                $q->where('thickness', '<=', 4)
                ->with(['selected_lipping_species_items' => function ($q2) use ($auth) {
                    $q2->where('selected_user_id', $auth->id);
                }]);
            }
        ])
        ->where('Status', 1)
        ->whereIn('editBy', $userIds)
        ->orderBy('SpeciesName', 'ASC')
        ->get();

        return view('SelectedOptions.lipping_species.index',compact('species', 'auth'));

    }

    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedLippingSpeciesItems::where('selected_user_id', $userId)
                ->whereNotIn('selected_lipping_species_items_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {

            $electedOption = LippingSpeciesItems::Where('id', $row['id'])->select('*')->first();

                if ($row['checked']) {

                    SelectedLippingSpeciesItems::updateOrCreate(

                        [
                            'selected_user_id' => $userId,
                            'selected_lipping_species_items_id' => $row['id']
                        ],

                        [
                            'selected_price' => $row['price'] ?? 0,
                            'selected_lipping_species_id' => $electedOption->lipping_species_id,
                            'selected_thickness' => $electedOption->thickness,
                            'selected_status' => $electedOption->status,
                        ]

                    );
                }
            }

        } else {

            SelectedLippingSpeciesItems::where('selected_user_id', $userId)->delete();

        }

        return response()->json(['status' => 'ok']);
    }

    public function exportSelected(Request $request)
    {
        $ids = $request->ids;

        $auth = auth()->user();

        $userIds = $auth->UserType == 2 ? [1, $auth->id] : [1];

        $items = LippingSpecies::with([
            'lipping_species_items' => function ($q) use ($auth, $ids) {
                $q->whereIn('id', $ids)
                ->where('thickness', '<=', 4)
                ->with(['selected_lipping_species_items' => function ($q2) use ($auth) {
                    $q2->where('selected_user_id', $auth->id);
                }]);
            }
        ])
        ->where('Status', 1)
        ->whereIn('editBy', $userIds)
        ->orderBy('SpeciesName', 'ASC')
        ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $headers = [
            'Species',
            'Inch',
            'MM',
            'Status',
            'Price / M3'
        ];

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold Heading
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Column width
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(18);

        $row = 2;

        foreach ($items as $species) {

            foreach ($species->lipping_species_items as $item) {

                $selected = $item->selected_lipping_species_items->first();

                if ($selected) {

                    $sheet->setCellValue('A'.$row, $species->SpeciesName);
                    $sheet->setCellValue('B'.$row, $item->thickness);
                    $sheet->setCellValue('C'.$row, number_format($item->thickness * 25.4, 1));
                    $sheet->setCellValue('D'.$row, $item->status ? 'Active' : 'Inactive');
                    $sheet->setCellValue('E'.$row, number_format($selected->selected_price ?? 0, 2));

                    $row++;
                }
            }
        }

        $lastRow = $row - 1;

        // Borders
        $sheet->getStyle('A1:E'.$lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);

        $filename = 'Timber_Species_Selected_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }
}
