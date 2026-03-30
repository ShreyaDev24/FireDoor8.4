<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlassType;
use App\Models\SelectedGlassType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class GlassTypeController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $items = GlassType::leftJoin('selected_glass_type as sg', function ($join) use ($auth) {
                $join->on('glass_type.id', '=', 'sg.glass_id')
                     ->where('sg.editBy', $auth->id);
            })
            ->whereIn('glass_type.EditBy', [$auth->id, 1])
            ->select(
                'glass_type.*',
                'sg.id as selectedId',
                'sg.selectedPrice'
            )
            ->orderBy('glass_type.GlassType')
            ->get();

        return view('SelectedOptions.glass_type.index', compact('items', 'auth'));
    }

    public function create()
    {
        return view('SelectedOptions.glass_type.create');
    }

    public function createStandard()
    {
        return view('SelectedOptions.glass_type.createStandard');
    }

    public function store(Request $request)
    {
        $request->validate([
            'GlassType'     => 'required|string',
            'GlassThickness' => 'required|numeric|min:0',
            'GlassIntegrity' => 'required',
            'price'          => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $slug = str_replace(' ', '_', $request->GlassType);

            $glass = GlassType::create([
                'Key'           => $slug,
                'GlassType'     => $request->GlassType,
                'GlassThickness'=> $request->GlassThickness,
                'GlassIntegrity'=> $request->GlassIntegrity,
                'GlazingBeads'  => json_encode($request->GlazingBeads ?? []),
                'VpAreaSize'    => $request->VpAreaSize,
                'status'        => $request->status ?? 1,
                'EditBy'        => auth()->id(),
                ...$this->coreMap($request),
                ...$this->fireRating($request),
            ]);

            if (auth()->id() != 1) {
                SelectedGlassType::create([
                    'glass_id'      => $glass->id,
                    'editBy'        => auth()->id(),
                    'selectedPrice'=> $request->price,
                ]);
            }
        });

        return redirect()->route('Glass-type.index')->with('success', 'Glass Type added successfully');
    }


    public function edit($id)
    {
        $item = GlassType::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.glass_type.edit', compact('item'));
    }

    public function editStandard($id)
    {
        $item = GlassType::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.glass_type.editStandard', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'GlassType'     => 'required|string',
            'GlassThickness' => 'required|numeric|min:0',
            'GlassIntegrity' => 'required',
            'price'          => 'nullable|numeric|min:0',
        ]);

        $glass = GlassType::findOrFail($id);

        DB::transaction(function () use ($request, $glass) {

            $slug = str_replace(' ', '_',$request->GlassType);

            $glass->update([
                'Key'           => $slug,
                'GlassType'     => $request->GlassType,
                'GlassThickness'=> $request->GlassThickness,
                'GlassIntegrity'=> $request->GlassIntegrity,
                'GlazingBeads'  => json_encode($request->GlazingBeads ?? []),
                'VpAreaSize'    => $request->VpAreaSize,
                'status'        => $request->status ?? 1,
                'EditBy'        => auth()->id(),
                ...$this->coreMap($request),
                ...$this->fireRating($request),
            ]);

            if (auth()->id() != 1) {
                SelectedGlassType::updateOrCreate(
                    [
                        'glass_id' => $glass->id,
                        'editBy'   => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price,
                    ]
                );
            }
        });

        return redirect()->route('Glass-type.index')->with('success', 'Updated successfully');
    }


    public function destroy($id)
    {
        GlassType::where('id', $id)->delete();
        SelectedGlassType::where('glass_id', $id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

   private function coreMap(Request $request): array
    {
        $cores = $request->input('core', []); // array of selected values

        return [
            'Streboard'       => in_array(1, $cores) ? 1 : null,
            'Halspan'         => in_array(2, $cores) ? 2 : null,
            'Flamebreak'      => in_array(7, $cores) ? 7 : null,
            'Stredor'         => in_array(8, $cores) ? 8 : null,
            'NormaDoorCore'   => in_array(3, $cores) ? 3 : null,
            'VicaimaDoorCore' => in_array(4, $cores) ? 4 : null,
            'Seadec'          => in_array(5, $cores) ? 5 : null,
            'Deanta'          => in_array(6, $cores) ? 6 : null,
            'MMM'             => in_array(9, $cores) ? 9 : null,
        ];
    }

    private function fireRating(Request $request): array
    {
        $firerating = $request->input('firerating', []);

        return [
            'NFR'  => in_array('NFR',  $firerating) ? 'NFR'  : null,
            'FD30' => in_array('FD30', $firerating) ? 'FD30' : null,
            'FD60' => in_array('FD60', $firerating) ? 'FD60' : null,
        ];
    }


    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedGlassType::where('editBy', $userId)
                ->whereNotIn('glass_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedGlassType::updateOrCreate(
                        [
                            'editBy' => $userId,
                            'glass_id' => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }
        } else {
            SelectedGlassType::where('editBy', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function exportSelected(Request $request)
    {
        $ids = $request->has('ids')
            ? (is_array($request->ids) ? $request->ids : json_decode($request->ids, true))
            : [];

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Invalid export data.');
        }

        $auth = auth()->user();

        $items = GlassType::leftJoin('selected_glass_type as sg', function ($join) use ($auth) {
                $join->on('glass_type.id', '=', 'sg.glass_id')
                     ->where('sg.editBy', $auth->id);
            })
            ->whereIn('glass_type.EditBy', [$auth->id, 1])
            ->whereIn('glass_type.id', $ids)
            ->select(
                'glass_type.*',
                'sg.id as selectedId',
                'sg.selectedPrice'
            )
            ->orderBy('glass_type.GlassType')
            ->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'Streboard',
            'Halspan',
            'Flamebreak',
            'Stredor',
            'Vicaima',
            'Seadec',
            'Deanta',
            'MMM',
            'NFR',
            'FD30',
            'FD60',
            'Glass Integrity',
            'Glass Type',
            'Glass Thickness',
            'Vp Area Size',
            'Price Per m²'
        ];

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold header
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Column width
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setWidth(18);
        }

        $row = 2;

        foreach ($items as $item) {

            $sheet->setCellValue('A'.$row, $item->Streboard ? 'Yes' : 'No');
            $sheet->setCellValue('B'.$row, $item->Halspan ? 'Yes' : 'No');
            $sheet->setCellValue('C'.$row, $item->Flamebreak ? 'Yes' : 'No');
            $sheet->setCellValue('D'.$row, $item->Stredor ? 'Yes' : 'No');
            $sheet->setCellValue('E'.$row, $item->VicaimaDoorCore ? 'Yes' : 'No');
            $sheet->setCellValue('F'.$row, $item->Seadec ? 'Yes' : 'No');
            $sheet->setCellValue('G'.$row, $item->Deanta ? 'Yes' : 'No');
            $sheet->setCellValue('H'.$row, $item->MMM ? 'Yes' : 'No');
            $sheet->setCellValue('I'.$row, $item->NFR ?? '-');
            $sheet->setCellValue('J'.$row, $item->FD30 ?? '-');
            $sheet->setCellValue('K'.$row, $item->FD60 ?? '-');
            $sheet->setCellValue('L'.$row, str_replace('_', ' ', $item->GlassIntegrity));
            $sheet->setCellValue('M'.$row, $item->GlassType);
            $sheet->setCellValue('N'.$row, $item->GlassThickness);
            $sheet->setCellValue('O'.$row, $item->VpAreaSize);
            $sheet->setCellValue('P'.$row, number_format($item->selectedPrice ?? 0, 2));

            $row++;
        }

        $lastRow = $row - 1;

        // Borders
        $sheet->getStyle('A1:P' . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Glass_Type_selected_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }
}
