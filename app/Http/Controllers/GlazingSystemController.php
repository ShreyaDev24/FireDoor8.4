<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlazingSystem;
use App\Models\SelectedGlazingSystem;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class GlazingSystemController extends Controller
{

    public function index()
    {
        $auth = auth()->user();

        $items = GlazingSystem::leftJoin('selected_glazing_system as sg', function ($join) use ($auth) {

                $join->on('glazing_system.id', '=', 'sg.glazingId')
                     ->where('sg.userId', $auth->id);

            })
            ->whereIn('glazing_system.editBy', [$auth->id, 1])
            ->select(
                'glazing_system.*',
                'sg.id as selectedId',
                'sg.selectedPrice'
            )
            ->orderBy('glazing_system.GlazingSystem')
            ->get();

        return view('SelectedOptions.glazing_system.index', compact('items','auth'));
    }


    public function create()
    {
        return view('SelectedOptions.glazing_system.create');
    }


    public function createStandard()
    {
        return view('SelectedOptions.glazing_system.createStandard');
    }


    public function store(Request $request)
    {
        $request->validate([
            'GlazingSystem' => 'required',
            'GlazingThickness' => 'required|numeric',
            'price' => 'nullable|numeric'
        ]);

        DB::transaction(function () use ($request) {

            $slug = str_replace(' ', '_', $request->GlazingSystem);

            $glazing = GlazingSystem::create([

                'Key' => $slug,

                'GlazingSystem' => $request->GlazingSystem,

                'GlazingThickness' => $request->GlazingThickness,

                'GlazingBeadFixingDetail' => $request->GlazingBeadFixingDetail,

                'test_ref' => $request->test_ref,

                'VPAreaSize' => $request->VPAreaSize,

                'Status' => $request->Status ?? 1,

                'editBy' => auth()->id(),

                ...$this->coreMap($request),

                ...$this->fireRating($request),

            ]);

            if (auth()->id() != 1) {

                SelectedGlazingSystem::create([

                    'glazingId' => $glazing->id,

                    'userId' => auth()->id(),

                    'selectedPrice' => $request->price,

                ]);
            }

        });

        return redirect()->route('Glazing-System.index')
            ->with('success', 'Created successfully');
    }


    public function edit($id)
    {
        $item = GlazingSystem::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.glazing_system.edit', compact('item'));
    }

    public function editStandard($id)
    {
        $item = GlazingSystem::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.glazing_system.editStandard', compact('item'));
    }


    public function update(Request $request, $id)
    {

        $glazing = GlazingSystem::findOrFail($id);

        DB::transaction(function () use ($request, $glazing) {

            $slug = str_replace(' ', '_', $request->GlazingSystem);

            $glazing->update([

                'Key' => $slug,

                'GlazingSystem' => $request->GlazingSystem,

                'GlazingThickness' => $request->GlazingThickness,

                'GlazingBeadFixingDetail' => $request->GlazingBeadFixingDetail,

                'test_ref' => $request->test_ref,

                'VPAreaSize' => $request->VPAreaSize,

                'Status' => $request->Status ?? 1,

                'editBy' => auth()->id(),

                ...$this->coreMap($request),

                ...$this->fireRating($request),

            ]);


            if (auth()->id() != 1) {

                SelectedGlazingSystem::updateOrCreate(

                    [
                        'glazingId' => $glazing->id,
                        'userId' => auth()->id(),
                    ],

                    [
                        'selectedPrice' => $request->price
                    ]
                );
            }

        });

        return redirect()->route('Glazing-System.index')
            ->with('success', 'Updated successfully');
    }


    public function destroy($id)
    {
        GlazingSystem::where('id', $id)->delete();

        SelectedGlazingSystem::where('glazingId', $id)->delete();

        return back()->with('success', 'Deleted');
    }


    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedGlazingSystem::where('userId', $userId)
                ->whereNotIn('glazingId', $keys)
                ->delete();


            foreach ($request->rows as $row) {

                if ($row['checked']) {

                    SelectedGlazingSystem::updateOrCreate(

                        [
                            'userId' => $userId,
                            'glazingId' => $row['id']
                        ],

                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]

                    );
                }
            }
        } else {
            SelectedGlazingSystem::where('userId', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }


    private function coreMap($request)
    {
        $core = $request->core ?? [];

        return [

            'Streboard' => in_array(1, $core) ? 1 : null,

            'Halspan' => in_array(2, $core) ? 2 : null,

            'Flamebreak' => in_array(7, $core) ? 7 : null,

            'Stredor' => in_array(8, $core) ? 8 : null,

            'NormaDoorCore' => in_array(3, $core) ? 3 : null,

            'VicaimaDoorCore' => in_array(4, $core) ? 4 : null,

            'Seadec' => in_array(5, $core) ? 5 : null,

            'Deanta' => in_array(6, $core) ? 6 : null,

            'MMM' => in_array(9, $core) ? 9 : null,

        ];
    }


    private function fireRating($request)
    {
        $rating = $request->firerating ?? [];

        return [

            'NFR' => in_array('NFR', $rating) ? 'NFR' : null,

            'FD30' => in_array('FD30', $rating) ? 'FD30' : null,

            'FD60' => in_array('FD60', $rating) ? 'FD60' : null,

        ];
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

        $items = GlazingSystem::leftJoin('selected_glazing_system as sg', function ($join) use ($auth) {

                $join->on('glazing_system.id', '=', 'sg.glazingId')
                     ->where('sg.userId', $auth->id);

            })
            ->whereIn('glazing_system.editBy', [$auth->id, 1])
            ->whereIn('glazing_system.id', $ids)
            ->select(
                'glazing_system.*',
                'sg.id as selectedId',
                'sg.selectedPrice'
            )
            ->orderBy('glazing_system.GlazingSystem')
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
            'Glazing Type',
            'Glazing Thickness',
            'GlazingBead FixingDetail',
            'Vp Area Size',
            'Price Per m²'
        ];

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold Header
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Column widths
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setWidth(18);
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
            $sheet->setCellValue('L'.$row, $item->GlazingSystem);
            $sheet->setCellValue('M'.$row, $item->GlazingThickness);
            $sheet->setCellValue('N'.$row, $item->GlazingBeadFixingDetail);
            $sheet->setCellValue('O'.$row, $item->VPAreaSize);
            $sheet->setCellValue('P'.$row, number_format($item->selectedPrice ?? 0, 2));

            $row++;
        }

        $lastRow = $row - 1;

        // Border
        $sheet->getStyle('A1:P' . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Glazing_System_selected_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

}
