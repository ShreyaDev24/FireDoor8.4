<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\SelectedColor;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class ColourListController extends Controller
{

    public function index()
    {
        $auth = auth()->user();

        $items = Color::leftJoin('selected_color as sc', function ($join) use ($auth) {

                $join->on('color.id', '=', 'sc.SelectedColorId')
                     ->where('sc.SelectedUserId', $auth->id);

            })
            ->whereIn('color.editBy', [$auth->id, 1])
            ->select(
                'color.*',
                'sc.id as selectedId',
                'sc.SelectedPrice'
            )
            ->orderBy('color.DoorLeafFacing')
            ->orderBy('color.ColorName')
            ->get()
            ->groupBy('DoorLeafFacing'); // ✅ group after fetch

        return view('SelectedOptions.color.index', compact('items','auth'));
    }


    public function create()
    {
        $facingTypes = Color::select('DoorLeafFacing')
                ->distinct()
                ->orderBy('DoorLeafFacing')
                ->pluck('DoorLeafFacing');


        return view('SelectedOptions.color.create', compact('facingTypes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'ColorName' => 'required',
            'RGB' => 'nullable',
            'Hex' => 'nullable',
            'EnglishName' => 'nullable',
            'DoorLeafFacing' => 'nullable',
            'ColorCost' => 'nullable|numeric'
        ]);

        DB::transaction(function () use ($request) {

            $DoorLeafFacingValue = $request->DoorLeafFacingValue;
            if($request->DoorLeafFacing == 'Kraft_Paper'){
                $DoorLeafFacingValue = 'Painted';
            }
            if($request->DoorLeafFacing == 'Veneer'){
                $DoorLeafFacingValue = 'Paint_Finish';
            }

            $color = Color::create([

                'ColorName' => $request->ColorName,
                'RGB' => $request->RGB,
                'Hex' => $request->Hex,
                'EnglishName' => $request->ColorName,
                'DoorLeafFacing' => $request->DoorLeafFacing,
                'DoorLeafFacingValue' => $DoorLeafFacingValue,
                'ColorCost' => 0,
                'Status' => $request->Status ?? 1,
                'editBy' => auth()->id(),

            ]);

            if (auth()->id() != 1) {

                SelectedColor::create([

                    'SelectedColorId' => $color->id,
                    'SelectedUserId' => auth()->id(),
                    'DoorLeafFacingName' => $request->DoorLeafFacing,
                    'SelectedPrice' => $request->price

                ]);
            }

        });

        return redirect()->route('Colour-List.index')
            ->with('success', 'Created successfully');
    }


    public function edit($id)
    {
        $item = Color::with([
            'selectedPrice' => function ($q) {
                $q->where('SelectedUserId', auth()->id());
            }
        ])->findOrFail($id);

        $facingTypes = Color::select('DoorLeafFacing')
                ->distinct()
                ->orderBy('DoorLeafFacing')
                ->pluck('DoorLeafFacing');


        return view('SelectedOptions.color.edit', compact('item','facingTypes'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'ColorName' => 'required',
            'RGB' => 'nullable',
            'Hex' => 'nullable',
            'EnglishName' => 'nullable',
            'DoorLeafFacing' => 'nullable',
            'ColorCost' => 'nullable|numeric'
        ]);

        $color = Color::findOrFail($id);

        DB::transaction(function () use ($request, $color) {

            $DoorLeafFacingValue = $request->DoorLeafFacingValue;
            if($request->DoorLeafFacing == 'Kraft_Paper'){
                $DoorLeafFacingValue = 'Painted';
            }
            if($request->DoorLeafFacing == 'Veneer'){
                $DoorLeafFacingValue = 'Paint_Finish';
            }

            $color->update([

                'ColorName' => $request->ColorName,
                'RGB' => $request->RGB,
                'Hex' => $request->Hex,
                'EnglishName' => $request->ColorName,
                'DoorLeafFacing' => $request->DoorLeafFacing,
                'DoorLeafFacingValue' => $DoorLeafFacingValue,
                'ColorCost' => 0,
                'Status' => $request->Status ?? 1,
                'editBy' => auth()->id(),

            ]);


            if (auth()->id() != 1) {

                SelectedColor::updateOrCreate(

                    [
                        'SelectedColorId' => $color->id,
                        'SelectedUserId' => auth()->id(),
                    ],

                    [
                        'DoorLeafFacingName' => $request->DoorLeafFacing,
                        'SelectedPrice' => $request->price
                    ]
                );
            }

        });

        return redirect()->route('Colour-List.index')
            ->with('success', 'Updated successfully');
    }


    public function destroy($id)
    {
        Color::where('id', $id)->delete();

        SelectedColor::where('SelectedColorId', $id)->delete();

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

            SelectedColor::where('SelectedUserId', $userId)
                ->whereNotIn('SelectedColorId', $keys)
                ->delete();


            foreach ($request->rows as $row) {

                if ($row['checked']) {

                    SelectedColor::updateOrCreate(

                        [
                            'SelectedUserId' => $userId,
                            'SelectedColorId' => $row['id']
                        ],

                        [
                            'SelectedPrice' => $row['price'] ?? 0
                        ]

                    );
                }
            }

        } else {

            SelectedColor::where('SelectedUserId', $userId)->delete();

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

        $items = Color::leftJoin('selected_color as sc', function ($join) use ($auth) {

                $join->on('color.id', '=', 'sc.SelectedColorId')
                     ->where('sc.SelectedUserId', $auth->id);

            })
            ->whereIn('color.editBy', [$auth->id, 1])
            ->whereIn('color.id', $ids)
            ->select(
                'color.*',
                'sc.id as selectedId',
                'sc.SelectedPrice'
            )
            ->orderBy('color.DoorLeafFacing')
            ->orderBy('color.ColorName')
            ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headings
            $headers = [
                'Color Name',
                'Hex',
                'English Name',
                'Door Leaf Facing',
                'Door Configuration',
                'Door Leaf Facing Value',
                'Price'
            ];

            $sheet->fromArray($headers, NULL, 'A1');

            // Bold headings
            $sheet->getStyle('A1:G1')->getFont()->setBold(true);

            // Column width
            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(25);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(22);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(15);

            $row = 2;

            foreach ($items as $item) {

                $sheet->setCellValue('A'.$row, $item->ColorName);
                $sheet->setCellValue('B'.$row, $item->Hex);
                $sheet->setCellValue('C'.$row, $item->EnglishName);
                $sheet->setCellValue('D'.$row, $item->DoorLeafFacing);
                $sheet->setCellValue('E'.$row, $item->doorConfiguration);
                $sheet->setCellValue('F'.$row, $item->DoorLeafFacingValue);
                $sheet->setCellValue('G'.$row, number_format($item->SelectedPrice ?? 0, 2));

                $row++;
            }

            $lastRow = $row - 1;

            $sheet->getStyle('A1:G' . $lastRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


            $writer = new Xlsx($spreadsheet);
            $filename = 'Color_selected_' . now()->format('Ymd_His') . '.xlsx';

            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename);
    }

}
