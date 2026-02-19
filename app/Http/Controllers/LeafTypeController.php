<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeafType;
use App\Models\SelectedLeafType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;


class LeafTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $auth = auth()->user();

        $items = LeafType::leftJoin('selected_leaf_type as slt', function ($join) use ($auth) {
                $join->on('leaf_type.id', '=', 'slt.leaf_id')
                    ->where('slt.editBy', $auth->id);   // use userId (recommended)
            })
            ->whereIn('leaf_type.EditBy', [$auth->id, 1])
            ->where(function ($query) {
                $query->whereNotNull('leaf_type.VicaimaDoorCore')
                    ->orWhereNotNull('leaf_type.Seadec')
                    ->orWhereNotNull('leaf_type.MMM')
                    ->orWhereNotNull('leaf_type.Deanta');
            })
            ->select(
                'leaf_type.*',
                'slt.id as selectedId',
                'slt.selectedPrice'
            )
            ->orderBy('leaf_type.LeafType')
            ->get();

        return view('SelectedOptions.leaf_type.index', compact('items', 'auth'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('SelectedOptions.leaf_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'LeafType'       => 'required|string',
            'price'          => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
        $str = Str::slug($request->LeafType, '_');
            $leaf = LeafType::create([
                'Key'            => $str,
                'LeafType'       => $request->LeafType,
                'UnderAttribute' => $str,
                'EditBy'         => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedLeafType::create([
                    'leaf_id'      => $leaf->id,
                    'selectedPrice'=> $request->price,
                    'editBy'       => auth()->id(),
                ]);
            }
        });

        return redirect()
            ->route('leaf-type.index')
            ->with('success', 'Leaf Type added successfully');
    }

    public function edit($id)
    {
        $item = LeafType::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.leaf_type.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'LeafType'       => 'required|string',
            'price'          => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $id) {

            $leaf = LeafType::findOrFail($id);
            $str = Str::slug($request->LeafType, '_');
            $leaf->update([
                'Key'            => $str,
                'LeafType'       => $request->LeafType,
                'UnderAttribute' => $str,
                'EditBy'         => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedLeafType::updateOrCreate(
                    [
                        'leaf_id' => $leaf->id,
                        'editBy'  => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price,
                    ]
                );
            }
        });

        return redirect()
            ->route('leaf-type.index')
            ->with('success', 'Leaf Type updated successfully');
    }

    public function destroy($id)
    {
        LeafType::where('id', $id)->delete();

        return back()->with('success', 'Deleted successfully');
    }

    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedLeafType::where('editBy', $userId)
                ->whereNotIn('leaf_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedLeafType::updateOrCreate(
                        [
                            'editBy'  => $userId,
                            'leaf_id' => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedLeafType::where('editBy', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
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
}
