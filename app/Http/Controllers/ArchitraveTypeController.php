<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchitraveType;
use App\Models\SelectedArchitraveType;
use Illuminate\Support\Str;
use DB;

class ArchitraveTypeController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $auth = auth()->user();

        $items = ArchitraveType::leftJoin('selected_architrave_type as sa', function ($join) use ($auth) {
                $join->on('architrave_type.id', '=', 'sa.architraveTypeId')
                     ->where('sa.userId', $auth->id);
            })
            ->whereIn('architrave_type.editBy', [$auth->id, 1])
            ->select(
                'architrave_type.*',
                'sa.id as selectedId',
                'sa.selectedPrice'
            )
            ->orderBy('architrave_type.ArchitraveType')
            ->get();

        return view('SelectedOptions.architrave_type.index', compact('items', 'auth'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('SelectedOptions.architrave_type.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'ArchitraveType' => 'required|string',
            'price'          => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $slug = Str::slug($request->ArchitraveType, '_');

            $architrave = ArchitraveType::create([
                'Key'             => $slug,
                'ArchitraveType'  => $request->ArchitraveType,
                'editBy'          => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedArchitraveType::create([
                    'architraveTypeId' => $architrave->id,
                    'userId'           => auth()->id(),
                    'selectedPrice'    => $request->price,
                ]);
            }
        });

        return redirect()->route('Architrave-Type.index')
            ->with('success', 'Architrave Type added successfully');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $item = ArchitraveType::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.architrave_type.edit', compact('item'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ArchitraveType' => 'required|string',
            'price'          => 'nullable|numeric|min:0',
        ]);

        $architrave = ArchitraveType::findOrFail($id);

        DB::transaction(function () use ($request, $architrave) {

            $slug = Str::slug($request->ArchitraveType, '_');

            $architrave->update([
                'Key'            => $slug,
                'ArchitraveType' => $request->ArchitraveType,
                'editBy'         => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {

                SelectedArchitraveType::updateOrCreate(
                    [
                        'architraveTypeId' => $architrave->id,
                        'userId'           => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price,
                    ]
                );
            }
        });

        return redirect()->route('Architrave-Type.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        ArchitraveType::where('id', $id)->delete();
        return back()->with('success', 'Deleted successfully');
    }

    /**
     * Core Mapping (Checkbox Columns)
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

            SelectedArchitraveType::where('userId', $userId)
                ->whereNotIn('architraveTypeId', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedArchitraveType::updateOrCreate(
                        [
                            'userId'        => $userId,
                            'architraveTypeId'  => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedArchitraveType::where('userId', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
