<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoorDimension;
use App\Models\Option;
use App\Models\SelectedDoordimension;
use Illuminate\Support\Str;
use App\Exports\LeafTypeSelectedExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class DoorDimensionStandardController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $items = DoorDimension::leftJoin('selected_doordimension as sd', function ($join) use ($auth) {
                $join->on('door_dimension.id', '=', 'sd.doordimension_id')
                     ->where('sd.doordimension_user_id', $auth->id);
            })
            ->whereIn('door_dimension.editBy', [$auth->id, 1])
            ->select(
                'door_dimension.*',
                'sd.id as selectedId',
                'sd.selected_sellingprice',
                'sd.selected_cost'
            )
            ->whereIn('door_dimension.configurableitems', [4,5,6,9])
            ->orderBy('door_dimension.mm_height')
            ->orderBy('door_dimension.mm_width')
            ->get();

        return view('SelectedOptions.door_dimension.index', compact('items', 'auth'));
    }

    public function create()
    {
        $auth = auth()->user();
        if($auth->UserType == 1){
            $leaftype = GetOptions(['leaf_type.Status' => 1], "","leaf_type");
        }else{
            $leaftype = GetOptions(['leaf_type.Status' => 1], "join","leaf_type");
        }

        return view('SelectedOptions.door_dimension.create', compact('leaftype'));
    }

    public function show($id)
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'configurableitems' => 'required|numeric',
            'leaf_type'         => 'required|string',
            'mm_height'         => 'required|numeric',
            'mm_width'          => 'required|numeric',
            'fire_rating'       => 'required|string',
            'code'              => 'required|string',
            'cost_price'        => 'nullable|numeric|min:0',
            'DoorDimensionPrice'=> 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $dimension = DoorDimension::create([
                'UserId'            => auth()->id(),
                'configurableitems' => $request->configurableitems,
                'leaf_type'         => $request->leaf_type,
                'code'              => $request->code,
                'inch_height'       => $request->inch_height,
                'inch_width'        => $request->inch_width,
                'mm_height'         => $request->mm_height,
                'mm_width'          => $request->mm_width,
                'fire_rating'       => $request->fire_rating,
                'door_leaf_finish'  => $request->door_leaf_finish,
                'door_leaf_facing'  => $request->door_leaf_facing,
                'cost_price'        => $request->cost_price,
                'selling_price'     => $request->cost_price,
                'editBy'            => auth()->id(),
            ]);

            if (auth()->user()->UserType != 1) {
                SelectedDoordimension::create([
                    'doordimension_id'       => $dimension->id,
                    'doordimension_user_id'  => auth()->id(),
                    'selected_configurableitems' => $request->configurableitems,
                    'selected_firerating'    => $request->fire_rating,
                    'selected_code'          => $request->code,
                    'selected_mm_height'     => $request->mm_height,
                    'selected_mm_width'      => $request->mm_width,
                    'selected_sellingprice'  => $request->cost_price,
                    'selected_cost'          => $request->DoorDimensionPrice,
                ]);
            }
        });


        return redirect()->route('Door-Dimension.index')
            ->with('success', 'Door dimension added successfully');
    }

    public function edit($id)
    {
        $item = DoorDimension::with([
            'selectedPrice' => function ($q) {
                $q->where('doordimension_user_id', auth()->id());
            }
        ])->findOrFail($id);

        $auth = auth()->user();
        if($auth->UserType == 1){
            $leaftype = GetOptions(['leaf_type.Status' => 1], "","leaf_type");
        }else{
            $leaftype = GetOptions(['leaf_type.Status' => 1], "join","leaf_type");
        }

        return view('SelectedOptions.door_dimension.edit', compact('item','leaftype'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'configurableitems' => 'required|numeric',
            'leaf_type'         => 'required|string',
            'mm_height'         => 'required|numeric',
            'mm_width'          => 'required|numeric',
            'fire_rating'       => 'required|string',
            'code'              => 'required|string',
            'cost_price'        => 'nullable|numeric|min:0',
            'DoorDimensionPrice'=> 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {

            $dimension = DoorDimension::findOrFail($id);

            $dimension->update([
                'UserId'            => auth()->id(),
                'configurableitems' => $request->configurableitems,
                'leaf_type'         => $request->leaf_type,
                'code'              => $request->code,
                'inch_height'       => $request->inch_height,
                'inch_width'        => $request->inch_width,
                'mm_height'         => $request->mm_height,
                'mm_width'          => $request->mm_width,
                'fire_rating'       => $request->fire_rating,
                'door_leaf_finish'  => $request->door_leaf_finish,
                'door_leaf_facing'  => $request->door_leaf_facing,
                'cost_price'        => $request->cost_price,
                'selling_price'     => $request->cost_price,
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
                        'selected_cost'         => $request->DoorDimensionPrice,
                        'selected_sellingprice' => $request->cost_price,
                    ]
                );
            }
        });

        return redirect()->route('Door-Dimension.index')
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
                ->whereIn('selected_configurableitems', [4,5,6,9])
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
            SelectedDoordimension::where('doordimension_user_id', $userId)->whereIn('selected_configurableitems', [4,5,6,9])->delete();
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

        $items = DoorDimension::leftJoin('selected_doordimension as sd', function ($join) use ($auth) {
                $join->on('door_dimension.id', '=', 'sd.doordimension_id')
                    ->where('sd.doordimension_user_id', $auth->id);
            })
            ->whereIn('door_dimension.id', $ids)
            ->whereIn('door_dimension.configurableitems', [4,5,6,9])
            ->whereIn('door_dimension.editBy', [$auth->id, 1])
            ->select(
                'door_dimension.code',
                'door_dimension.mm_height',
                'door_dimension.mm_width',
                'door_dimension.fire_rating',
                'door_dimension.configurableitems',
                'door_dimension.inch_height',
                'door_dimension.inch_width',
                'door_dimension.door_leaf_facing',
                'door_dimension.leaf_type',
                'sd.selected_sellingprice',
                'sd.selected_cost'
            )
            ->orderBy('door_dimension.configurableitems')
            ->orderBy('door_dimension.mm_height')
            ->orderBy('door_dimension.mm_width')
            ->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $headers = [
            'Code',
            'Height (mm)',
            'Width (mm)',
            'Height (inch)',
            'Width (inch)',
            'Fire Rating',
            'Leaf Type',
            'Door Leaf Facing',
            'Configurable Item',
            'Cost Price'
        ];

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold heading
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(22);
        $sheet->getColumnDimension('J')->setWidth(18);

        $row = 2;

        foreach ($items as $item) {

            $sheet->setCellValue('A'.$row, $item->code);
            $sheet->setCellValue('B'.$row, $item->mm_height);
            $sheet->setCellValue('C'.$row, $item->mm_width);
            $sheet->setCellValue('D'.$row, $item->inch_height);
            $sheet->setCellValue('E'.$row, $item->inch_width);
            $sheet->setCellValue('F'.$row, $item->fire_rating);
            $sheet->setCellValue('G'.$row, $item->leaf_type);
            $sheet->setCellValue('H'.$row, $item->door_leaf_facing);
            $sheet->setCellValue('I'.$row, configurationDoor($item->configurableitems));
            $sheet->setCellValue('J'.$row, number_format($item->selected_cost ?? 0, 2));

            $row++;
        }

        $lastRow = $row - 1;

        // Borders
        $sheet->getStyle('A1:J'.$lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'door_dimensions_selected_'.now()->format('Ymd_His').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }
}
