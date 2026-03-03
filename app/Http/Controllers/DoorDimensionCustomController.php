<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoorDimension;
use App\Models\IntumescentSealLeafType;
use App\Models\SelectedDoordimension;
use Illuminate\Support\Str;
use App\Exports\LeafTypeSelectedExport;
use Maatwebsite\Excel\Facades\Excel;
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

        $items = DoorDimension::leftJoin('selected_doordimension as sd', function ($join) use ($auth) {
                $join->on('door_dimension.id', '=', 'sd.doordimension_id')
                    ->where('sd.doordimension_user_id', $auth->id);
            })
            ->whereIn('door_dimension.id', $ids)
            ->whereIn('door_dimension.configurableitems', [1,2,7,8])
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

        $filename = 'door_dimensions_selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Height (mm)',
                'Width (mm)',
                'Fire Rating',
                'Configurable Item',
                'Cost Price'
            ]);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->mm_height,
                    $item->mm_width,
                    $item->fire_rating,
                    configurationDoor($item->configurableitems),
                    number_format($item->selected_cost ?? 0, 2),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

   public function updateSelectOptionCostCustome(Request $request)
    {
        $selectedOption = SelectedDoordimension::find($request->selectedId);

        if ($selectedOption) {

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
