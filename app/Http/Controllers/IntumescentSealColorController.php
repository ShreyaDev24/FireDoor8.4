<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IntumescentSealColor;
use App\Models\SelectedIntumescentSealColor;
use Illuminate\Support\Str;
use DB;

class IntumescentSealColorController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $auth = auth()->user();

        $items = IntumescentSealColor::leftJoin(
                'selected_intumescent_seal_color as s',
                function ($join) use ($auth) {
                    $join->on('intumescent_seal_color.id', '=', 's.intumescentSealColorId')
                         ->where('s.userId', $auth->id);
                }
            )
            ->whereIn('intumescent_seal_color.editBy', [$auth->id, 1])
            ->select(
                'intumescent_seal_color.*',
                's.id as selectedId',
                's.selectedPrice'
            )
            ->orderBy('intumescent_seal_color.IntumescentSealColor')
            ->get();

        return view('SelectedOptions.Intumescent_Seal_Color.index', compact('items', 'auth'));
    }

    /**
     * CREATE
    */
    public function create()
    {
        return view('SelectedOptions.Intumescent_Seal_Color.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'IntumescentSealColor' => 'required|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $slug = str_replace(' ', '_', $request->IntumescentSealColor);

            $seal = IntumescentSealColor::create([
                'Key' => $slug,
                'IntumescentSealColor' => $request->IntumescentSealColor,
                'Status' => 1,
                'editBy' => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedIntumescentSealColor::create([
                    'intumescentSealColorId' => $seal->id,
                    'userId' => auth()->id(),
                    'selectedPrice' => $request->price ?? 0,
                ]);
            }
        });

        return redirect()->route('Intumescent-Seal-Color.index')
            ->with('success', 'Added successfully');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $item = IntumescentSealColor::with([
            'selectedPrice' => function ($q) {
                $q->where('userId', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.Intumescent_Seal_Color.edit', compact('item'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'IntumescentSealColor' => 'required|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $seal = IntumescentSealColor::findOrFail($id);

        DB::transaction(function () use ($request, $seal) {

            $slug = str_replace(' ', '_', $request->IntumescentSealColor);

            $seal->update([
                'Key' => $slug,
                'IntumescentSealColor' => $request->IntumescentSealColor,
                'editBy' => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedIntumescentSealColor::updateOrCreate(
                    [
                        'intumescentSealColorId' => $seal->id,
                        'userId' => auth()->id(),
                    ],
                    [
                        'selectedPrice' => $request->price ?? 0,
                    ]
                );
            }
        });

        return redirect()->route('Intumescent-Seal-Color.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        IntumescentSealColor::where('id', $id)->delete();
        return back()->with('success', 'Deleted successfully');
    }

    /**
     * CORE MAP
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

            SelectedIntumescentSealColor::where('userId', $userId)
                ->whereNotIn('intumescentSealColorId', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedIntumescentSealColor::updateOrCreate(
                        [
                            'userId'        => $userId,
                            'intumescentSealColorId'  => $row['id']
                        ],
                        [
                            'selectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedIntumescentSealColor::where('userId', $userId)->delete();
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

        $items = IntumescentSealColor::leftJoin(
                'selected_intumescent_seal_color as s',
                function ($join) use ($auth) {
                    $join->on('intumescent_seal_color.id', '=', 's.intumescentSealColorId')
                         ->where('s.userId', $auth->id);
                }
            )
            ->whereIn('intumescent_seal_color.id', $ids)
            ->select(
                'intumescent_seal_color.*',
                's.id as selectedId',
                's.selectedPrice'
            )
            ->orderBy('intumescent_seal_color.IntumescentSealColor')
            ->get();

        $filename = 'IntumescentSealColor_selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');
            fputcsv($file, ['Intumescent Seal Color', 'Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'Vicaima', 'Seadec', 'Deanta', 'MMM', 'Price Per m²']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->IntumescentSealColor,
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
