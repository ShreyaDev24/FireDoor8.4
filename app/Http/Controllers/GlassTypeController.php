<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlassType;
use App\Models\SelectedGlassType;
use Illuminate\Support\Str;
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

            $slug = Str::slug($request->GlassType, '_');

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

            $slug = Str::slug($request->GlassType, '_');

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
}
