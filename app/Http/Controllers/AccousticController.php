<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accoustics;
use App\Models\SelectedAccoustics;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class AccousticController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $auth = auth()->user();

        $items = Accoustics::leftJoin('selected_accoustics as sa', function ($join) use ($auth) {
                $join->on('accoustics.id', '=', 'sa.accousticsId')
                     ->where('sa.userId', $auth->id);
            })
            ->whereIn('accoustics.editBy', [$auth->id, 1])
            ->select(
                'accoustics.*',
                'sa.id as selectedId',
                'sa.selectedPrice'
            )
            ->orderBy('accoustics.Accoustics')
            ->get();

        return view('SelectedOptions.accoustics.index', compact('items', 'auth'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('SelectedOptions.accoustics.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'Accoustics'       => 'required|string',
            'AccousticsOption' => 'required|string',
            'price'            => 'nullable|numeric|min:0',
            'file'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $imageName = null;

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/Options'), $imageName);
        }

        DB::transaction(function () use ($request, $imageName) {

            $slug = str_replace(' ', '_',$request->Accoustics);

            $accoustic = Accoustics::create([
                'Key'            => $slug,
                'Accoustics'     => $request->Accoustics,
                'UnderAttribute' => $request->AccousticsOption,
                'file'           => $imageName,   // ✅ correct
                'editBy'         => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedAccoustics::create([
                    'accousticsId'  => $accoustic->id,
                    'userId'        => auth()->id(),
                    'selectedPrice' => $request->price,
                ]);
            }
        });

        return redirect()
            ->route('accoustics.index')
            ->with('success', 'Accoustics added successfully');
    }


    /**
     * EDIT
     */
    public function edit($id)
    {
        $item = Accoustics::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.accoustics.edit', compact('item'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Accoustics'       => 'required|string',
            'AccousticsOption' => 'required|string',
            'price'            => 'nullable|numeric|min:0',
            'file' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $accoustic = Accoustics::findOrFail($id);

        $imageName = $accoustic->file; // keep old image

        if ($request->hasFile('file')) {

            // delete old image
            if (!empty($accoustic->file) &&
                file_exists(public_path('uploads/Options/' . $accoustic->file))) {

                unlink(public_path('uploads/Options/' . $accoustic->file));
            }

            $image = $request->file('file');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/Options'), $imageName);
        }

        DB::transaction(function () use ($request, $accoustic, $imageName) {

            $slug = str_replace(' ', '_',$request->Accoustics);

            $accoustic->update([
                'Key'            => $slug,
                'Accoustics'     => $request->Accoustics,
                'UnderAttribute' => $request->AccousticsOption,
                'file'           => $imageName,
                'editBy'         => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {

                SelectedAccoustics::updateOrCreate(
                    [
                        'accousticsId' => $accoustic->id,
                        'userId'       => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price,
                    ]
                );
            }
        });

        return redirect()
            ->route('accoustics.index')
            ->with('success', 'Accoustics updated successfully');
    }


    /**
     * DELETE
     */
    public function destroy($id)
    {
        Accoustics::where('id', $id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    /**
     * BULK UPDATE (Checkbox Selection)
     */
    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedAccoustics::where('userId', $userId)
                ->whereNotIn('accousticsId', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedAccoustics::updateOrCreate(
                        [
                            'userId'        => $userId,
                            'accousticsId'  => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedAccoustics::where('userId', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Core Mapping (From Image Columns)
     */
    private function coreMap(Request $request)
    {
        return [
            'Streboard'       => $request->has('Streboard') ? 1 : null,
            'Halspan'         => $request->has('Halspan') ? 2 : null,
            'Flamebreak'      => $request->has('Flamebreak') ? 7 : null,
            'Stredor'         => $request->has('Stredor') ? 8 : null,
            'NormaDoorCore'   => $request->has('NormaDoorCore') ? 3 : null,
            'VicaimaDoorCore' => $request->has('VicaimaDoorCore') ? 4 : null,
            'Seadec'          => $request->has('Seadec') ? 5 : null,
            'Deanta'          => $request->has('Deanta') ? 6 : null,
            'MMM'             => $request->has('MMM') ? 9 : null,
        ];
    }

    public function exportSelected(Request $request)
    {
        $ids = json_decode($request->ids, true);

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Invalid export data.');
        }

        $auth = auth()->user();

        $items = Accoustics::leftJoin('selected_accoustics as sa', function ($join) use ($auth) {
                $join->on('accoustics.id', '=', 'sa.accousticsId')
                     ->where('sa.userId', $auth->id);
            })
            ->whereIn('accoustics.id', $ids)
            ->select(
                'accoustics.*',
                'sa.id as selectedId',
                'sa.selectedPrice'
            )
            ->orderBy('accoustics.Accoustics')
            ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headings
            $headers = [
                'Accoustics',
                'Streboard',
                'Halspan',
                'Flamebreak',
                'Stredor',
                'Vicaima',
                'Seadec',
                'Deanta',
                'MMM',
                'Price Per m²'
            ];

            $sheet->fromArray($headers, NULL, 'A1');

            // Bold heading
            $sheet->getStyle('A1:J1')->getFont()->setBold(true);

            // Column widths
            $sheet->getColumnDimension('A')->setWidth(35);
            $sheet->getColumnDimension('B')->setWidth(12);
            $sheet->getColumnDimension('C')->setWidth(12);
            $sheet->getColumnDimension('D')->setWidth(12);
            $sheet->getColumnDimension('E')->setWidth(12);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(12);
            $sheet->getColumnDimension('H')->setWidth(12);
            $sheet->getColumnDimension('I')->setWidth(12);
            $sheet->getColumnDimension('J')->setWidth(18);

            $row = 2;

            foreach ($items as $item) {

                $sheet->setCellValue('A'.$row, $item->Accoustics);
                $sheet->setCellValue('B'.$row, $item->Streboard ? 'Yes' : 'No');
                $sheet->setCellValue('C'.$row, $item->Halspan ? 'Yes' : 'No');
                $sheet->setCellValue('D'.$row, $item->Flamebreak ? 'Yes' : 'No');
                $sheet->setCellValue('E'.$row, $item->Stredor ? 'Yes' : 'No');
                $sheet->setCellValue('F'.$row, $item->VicaimaDoorCore ? 'Yes' : 'No');
                $sheet->setCellValue('G'.$row, $item->Seadec ? 'Yes' : 'No');
                $sheet->setCellValue('H'.$row, $item->Deanta ? 'Yes' : 'No');
                $sheet->setCellValue('I'.$row, $item->MMM ? 'Yes' : 'No');
                $sheet->setCellValue('J'.$row, number_format($item->selectedPrice ?? 0, 2));

                $row++;
            }

            $lastRow = $row - 1;

            // Borders
            $sheet->getStyle('A1:J' . $lastRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $writer = new Xlsx($spreadsheet);

            $filename = 'accoustics_selected_' . now()->format('Ymd_His') . '.xlsx';

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename);
    }
}
