<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoorLeafFacing;
use App\Models\SelectedDoorLeafFacing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;


class DoorLeafFacingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $auth = auth()->user();

        $items = DoorLeafFacing::leftJoin('selected_door_leaf_facing as sdf', function ($join) use ($auth) {
                $join->on('door_leaf_facing.id', '=', 'sdf.doorLeafFacingId')
                    ->where('sdf.userId', $auth->id);
            })
            ->whereIn('door_leaf_facing.editBy', [$auth->id, 1])
            ->select(
                'door_leaf_facing.*',
                'sdf.id as selectedId',
                'sdf.selectedPrice'
            )
            ->orderBy('door_leaf_facing.doorLeafFacing')
            ->orderBy('door_leaf_facing.doorLeafFacingValue')
            ->get();

        return view('SelectedOptions.door_leaf_facing.index', compact('items', 'auth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myAdminGroup = CompanyUsers();
        $UserId = array_merge(['1'], $myAdminGroup);

        if(Auth::user()->UserType == 1){
            $leaftypemmm = DB::table('leaf_type')->where('MMM',9)->get();
            $leaftype2 = DB::table('leaf_type')->where('Seadec',5)->get();
            $leaftypevicima = DB::table('leaf_type')->where('VicaimaDoorCore',4)->get();
        } else{
            $leaftypemmm = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('MMM',9)->get();
            $leaftype2 = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('Seadec',5)->get();
            $leaftypevicima = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('VicaimaDoorCore',4)->get();
        }

        return view('SelectedOptions.door_leaf_facing.create',
            compact('leaftypemmm','leaftype2','leaftypevicima'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'DoorLeafOption'       => 'required|string',
            'doorLeafFacingValue'  => 'required|string',
            'price'                => 'nullable|numeric|min:0'
        ]);

        // ensure at least one core is selected
        if (!collect($this->coreMap($request))->filter()->count()) {
            return back()->withErrors(['core' => 'Please select at least one configuration']);
        }

        DB::transaction(function () use ($request) {

            $door = DoorLeafFacing::create([
                'Key'                 => str_replace(' ', '_', $request->doorLeafFacingValue),
                'doorLeafFacing'      => $request->DoorLeafOption,
                'doorLeafFacingValue' => $request->doorLeafFacingValue,
                'editBy'              => auth()->id(),
                ...$this->coreMap($request),
            ]);

            // non-admin price save
            if (auth()->id() != 1) {
                SelectedDoorLeafFacing::create([
                    'doorLeafFacingId' => $door->id,
                    'selectedPrice'    => $request->price,
                    'userId'           => auth()->id(),
                ]);
            }
        });

        return redirect()
            ->route('door-leaf-facing.index')
            ->with('success', 'Door Leaf Facing added successfully');
    }


    private function coreMap(Request $request)
    {
        return [
            'Streboard'       => $request->has('Streboard') ? 1 : null,
            'Halspan'         => $request->has('Halspan') ? 2 : null,
            'NormaDoorCore'   => $request->has('NormaDoorCore') ? 3 : null,
            'VicaimaDoorCore' => $request->has('VicaimaDoorCore') ? 4 : null,
            'Seadec'          => $request->has('Seadec') ? 5 : null,
            'Deanta'          => $request->has('Deanta') ? 6 : null,
            'Flamebreak'      => $request->has('Flamebreak') ? 7 : null,
            'Stredor'         => $request->has('Stredor') ? 8 : null,
            'MMM'             => $request->has('MMM') ? 9 : null,
        ];
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $myAdminGroup = CompanyUsers();
        $UserId = array_merge(['1'], $myAdminGroup);

        $item = DoorLeafFacing::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        if(Auth::user()->UserType == 1){
            $leaftypemmm = DB::table('leaf_type')->where('MMM',9)->get();
            $leaftype2 = DB::table('leaf_type')->where('Seadec',5)->get();
            $leaftypevicima = DB::table('leaf_type')->where('VicaimaDoorCore',4)->get();
        } else{
            $leaftypemmm = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('MMM',9)->get();
            $leaftype2 = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('Seadec',5)->get();
            $leaftypevicima = DB::table('leaf_type')->whereIn('EditBy',$UserId)->where('VicaimaDoorCore',4)->get();
        }

        return view(
            'SelectedOptions.door_leaf_facing.edit',
            compact('item','leaftypemmm','leaftype2','leaftypevicima')
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'DoorLeafOption'       => 'required|string',
            'doorLeafFacingValue'  => 'required|string',
            'price'                => 'nullable|numeric|min:0'
        ]);

        if (!collect($this->coreMap($request))->filter()->count()) {
            return back()->withErrors(['core' => 'Please select at least one configuration']);
        }

        DB::transaction(function () use ($request, $id) {

            $door = DoorLeafFacing::findOrFail($id);

            $door->update([
                'Key'                 => str_replace(' ', '_', $request->doorLeafFacingValue),
                'doorLeafFacing'      => $request->DoorLeafOption,
                'doorLeafFacingValue' => $request->doorLeafFacingValue,
                'editBy'              => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedDoorLeafFacing::updateOrCreate(
                    [
                        'doorLeafFacingId' => $door->id,
                        'userId'           => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price,
                    ]
                );
            }
        });

        return redirect()
            ->route('door-leaf-facing.index')
            ->with('success', 'Door Leaf Facing updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DoorLeafFacing::where('id', $id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        // Collect checked IDs from DataTables payload
        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            // 1️⃣ Remove unselected options
            SelectedDoorLeafFacing::where('userId', $userId)
                ->whereNotIn('doorLeafFacingId', $keys)
                ->delete();

            // 2️⃣ Insert / Update selected options
            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedDoorLeafFacing::updateOrCreate(
                        [
                            'userId' => $userId,
                            'doorLeafFacingId' => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            // 3️⃣ If nothing selected → remove all
            SelectedDoorLeafFacing::where('userId', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function exportSelected(Request $request)
    {
        $ids = json_decode($request->ids, true);

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Invalid export data.');
        }

        $auth = auth()->user();

        $items = DoorLeafFacing::leftJoin('selected_door_leaf_facing as sdf', function ($join) use ($auth) {
                $join->on('door_leaf_facing.id', '=', 'sdf.doorLeafFacingId')
                    ->where('sdf.userId', $auth->id);
            })
            ->whereIn('door_leaf_facing.id', $ids)
            ->select(
                'door_leaf_facing.*',
                'sdf.id as selectedId',
                'sdf.selectedPrice'
            )
            ->orderBy('door_leaf_facing.doorLeafFacing')
            ->orderBy('door_leaf_facing.doorLeafFacingValue')
            ->get();


        $filename = 'Door_Leaf_Facing_selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');
            fputcsv($file, ['Option', 'Name', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->doorLeafFacing,
                    $item->doorLeafFacingValue,
                    $item->Streboard ? 'Yes' : 'No',
                    $item->Halspan ? 'Yes' : 'No',
                    $item->Flamebreak ? 'Yes' : 'No',
                    $item->Stredor ? 'Yes' : 'No',
                    $item->VicaimaDoorCore ? 'Yes' : 'No',
                    $item->Seadec ? 'Yes' : 'No',
                    $item->Deanta ? 'Yes' : 'No',
                    $item->MMM ? 'Yes' : 'No',
                    number_format($item->selectedPrice ?? 0, 2),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}
