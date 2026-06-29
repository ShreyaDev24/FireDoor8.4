<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoorDimension;
use App\Models\IntumescentSealLeafType;
use App\Models\SelectedDoordimension;
use Illuminate\Support\Str;
use App\Exports\LeafTypeSelectedExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class DoorDimensionCustomController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $items = DoorDimension::with([
            'leafTypes',
            'selectedPrice' => function ($q) use ($auth) {
                $q->where('doordimension_user_id', $auth->id);
            }
        ])
        ->whereIn('editBy', [$auth->id, 1])
        ->whereIn('configurableitems', [1,2,7,8])
        ->orderBy('mm_height')
        ->orderBy('mm_width')
        ->get();

        return view('SelectedOptions.door_dimension_custom.index', compact('items', 'auth'));
    }

    public function create()
    {
        $intumenseLeafType = IntumescentSealLeafType::where('status',1)->get();

        return view('SelectedOptions.door_dimension_custom.create', compact('intumenseLeafType'));
    }

    public function show($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'configurableitems' => 'required|numeric',
            'mm_height'         => 'required|numeric',
            'mm_width'          => 'required|numeric',
            'fire_rating'       => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

            $dimension = DoorDimension::create([
                'UserId'            => auth()->id(),
                'configurableitems' => $request->configurableitems,
                'mm_height'         => $request->mm_height,
                'mm_width'          => $request->mm_width,
                'fire_rating'       => $request->fire_rating,
                'editBy'            => auth()->id(),
            ]);

            if (auth()->user()->UserType != 1) {
                SelectedDoordimension::create([
                    'doordimension_id'       => $dimension->id,
                    'doordimension_user_id'  => auth()->id(),
                    'selected_configurableitems' => $request->configurableitems,
                    'selected_firerating'    => $request->fire_rating,
                    'selected_mm_height'     => $request->mm_height,
                    'selected_mm_width'      => $request->mm_width,
                    'custome_door_selected_cost'  => $request->prices ?? 0,
                ]);
            }
        });

        return redirect()->route('Door-Dimension-Custom.index')
            ->with('success', 'Door dimension added successfully');
    }

    public function edit($id)
    {
        $item = DoorDimension::with([
            'selectedPrice' => function ($q) {
                $q->where('doordimension_user_id', auth()->id());
            }
        ])->findOrFail($id);

        $intumenseLeafType = IntumescentSealLeafType::where('status',1)->get();

        return view('SelectedOptions.door_dimension_custom.edit', compact('item','intumenseLeafType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'configurableitems' => 'required|numeric',
            'mm_height'         => 'required|numeric',
            'mm_width'          => 'required|numeric',
            'fire_rating'       => 'required|string',
        ]);

        DB::transaction(function () use ($request, $id) {

            $dimension = DoorDimension::findOrFail($id);

            $dimension->update([
                'UserId'            => auth()->id(),
                'configurableitems' => $request->configurableitems,
                'mm_height'         => $request->mm_height,
                'mm_width'          => $request->mm_width,
                'fire_rating'       => $request->fire_rating,
                'editBy'            => auth()->id(),
            ]);

            if (auth()->user()->UserType != 1) {
                SelectedDoordimension::updateOrCreate(
                    [
                        'doordimension_id'      => $dimension->id,
                        'doordimension_user_id' => auth()->id(),
                    ],
                    [
                        'selected_configurableitems' => $request->configurableitems,
                        'selected_firerating'   => $request->fire_rating,
                        'selected_code'         => $request->code,
                        'selected_mm_height'    => $request->mm_height,
                        'selected_mm_width'     => $request->mm_width,
                        'custome_door_selected_cost'  => $request->prices ?? 0,
                    ]
                );
            }
        });

        return redirect()->route('Door-Dimension-Custom.index')
            ->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        DoorDimension::where('id', $id)->delete();
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

            SelectedDoordimension::where('doordimension_user_id', $userId)
                ->whereIn('selected_configurableitems', [1,2,7,8])
                ->whereNotIn('doordimension_id', $keys)
                ->delete();

            $doors = DoorDimension::whereIn('id', $keys)->get()->keyBy('id');

            foreach ($request->rows as $row) {
                if (!$row['checked']) continue;

                $door = $doors[$row['id']] ?? null;
                if (!$door) continue;

                SelectedDoordimension::updateOrCreate(
                    [
                        'doordimension_user_id' => $userId,
                        'doordimension_id'      => $door->id,
                    ],
                    [
                        'selected_configurableitems' => $door->configurableitems,
                        'selected_firerating'        => $door->fire_rating,
                        'selected_code'              => $door->code,
                        'selected_mm_height'         => $door->mm_height,
                        'selected_mm_width'          => $door->mm_width,
                        'selected_sellingprice'      => $door->selling_price,
                        'selected_cost'              => $row['price'] ?? 0,
                    ]
                );
            }

        } else {
            SelectedDoordimension::where('doordimension_user_id', $userId)->whereIn('selected_configurableitems', [1,2,7,8])->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function exportSelected(Request $request)
    {
        $auth = auth()->user();

        $ids = $request->has('ids')
            ? (is_array($request->ids) ? $request->ids : json_decode($request->ids, true))
            : [];

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Invalid export data.');
        }

        $items = DoorDimension::with([
                'leafTypes',
                'selectedPrice' => function ($q) use ($auth) {
                    $q->where('doordimension_user_id', $auth->id);
                }
            ])
            ->whereIn('door_dimension.id', $ids)
            ->whereIn('editBy', [$auth->id, 1])
            ->whereIn('configurableitems', [1,2,7,8])
            ->orderBy('door_dimension.configurableitems')
            ->orderBy('mm_height')
            ->orderBy('mm_width')
            ->get();

        $leafTypes = IntumescentSealLeafType::where('status',1)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'Height (mm)',
            'Width (mm)',
            'Fire Rating',
            'Configurable Item',
        ];

        foreach ($leafTypes as $leaf) {
            $headers[] = $leaf->leaf_type_key . ' ' . configurationDoor($leaf->configurableitems) . ' Cost';
        }

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold Header
        $lastColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);

        // Column Width
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);

        $colIndex = 5;
        foreach ($leafTypes as $leaf) {
            $sheet->getColumnDimensionByColumn($colIndex)->setWidth(22);
            $colIndex++;
        }

        $row = 2;

        foreach ($items as $item) {

            $data = [
                $item->mm_height,
                $item->mm_width,
                $item->fire_rating,
                configurationDoor($item->configurableitems),
            ];

            $costs = $item->selectedPrice->custome_door_selected_cost ?? [];

            foreach ($leafTypes as $leaf) {

                $value = $costs[$leaf->id] ?? 0;

                $data[] = is_numeric($value)
                    ? number_format((float)$value, 2)
                    : 0;
            }

            $sheet->fromArray($data, NULL, 'A' . $row);

            $row++;
        }

        $lastRow = $row - 1;

        // Borders
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'door_dimensions_Custom_selected_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

   public function updateSelectOptionCostCustome(Request $request)
    {
        $selectedOption = SelectedDoordimension::find($request->selectedId);

        if ($selectedOption) {
            dd(
    $selectedOption->custome_door_selected_cost,
    gettype($selectedOption->custome_door_selected_cost)
);

            $costs = $selectedOption->custome_door_selected_cost ?? [];

            $costs[(int)$request->leafid] = $request->price; // IMPORTANT: no []

            $selectedOption->custome_door_selected_cost = $costs;

            $selectedOption->save();

            return response()->json([
                "status" => "ok",
                "msg" => "Option cost updated"
            ]);
        }

        return response()->json([
            "status" => "error",
            "msg" => "Option not found"
        ]);
    }
}
