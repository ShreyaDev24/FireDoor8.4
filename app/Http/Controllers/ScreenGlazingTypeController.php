<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScreenGlassType;
use App\Models\ScreenGlazingType;
use App\Models\SelectedScreenGlazing;
use DB;

class ScreenGlazingTypeController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $auth = auth()->user();

        $items = ScreenGlazingType::with('glassType')
            ->leftJoin('selected_screen_glazing as sg', function ($join) use ($auth) {

                $join->on('screen_glazing_type.id', '=', 'sg.glazing_id')
                     ->where('sg.editBy', $auth->id);

            })
            ->whereIn('screen_glazing_type.EditBy', [$auth->id, 1])
            ->select(
                'screen_glazing_type.*',
                'sg.id as selectedId',
                'sg.glazingSelectedPrice'
            )
            ->whereNotNull('screen_glazing_type.GlazingSystem')
            ->orderBy('screen_glazing_type.GlazingSystem', 'ASC')
            ->get();

        return view('SelectedOptions.screen_glazing_type.index', compact('items', 'auth'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        $auth = auth()->user();
        $screenGlassType = ScreenGlassType::whereIn('EditBy', [$auth->id, 1])->get();

        return view('SelectedOptions.screen_glazing_type.create', compact('screenGlassType'));
    }
    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([

            'FireRating' => 'required',
            'ScreenGlassId' => 'required',
            'GlazingSystem' => 'required',
            'GlazingThickness' => 'required|numeric',
            'Beading' => 'required',
            'BeadingHeight' => 'required|numeric',
            'BeadingWidth' => 'required|numeric',
            'FixingDetails' => 'required',
            'price' => 'nullable|numeric'

        ]);

        DB::transaction(function () use ($request) {

            $glazing = ScreenGlazingType::create([

                'FireRating' => $request->FireRating,
                'ScreenGlassId' => $request->ScreenGlassId,
                'GlazingSystem' => $request->GlazingSystem,
                'GlazingThickness' => $request->GlazingThickness,
                'Beading' => $request->Beading,
                'BeadingHeight' => $request->BeadingHeight,
                'BeadingWidth' => $request->BeadingWidth,
                'FixingDetails' => $request->FixingDetails,
                'EditBy' => auth()->id()

            ]);

            if(auth()->id() != 1)
            {
                SelectedScreenGlazing::create([

                    'glazing_id' => $glazing->id,
                    'editBy' => auth()->id(),
                    'glazingSelectedPrice' => $request->price ?? 0

                ]);
            }

        });

        return redirect()
            ->route('Screen-Glazing-Type.index')
            ->with('success','Created successfully');
    }


    /**
     * EDIT
     */
    public function edit($id)
    {
        $auth = auth()->user();
        $screenGlassType = ScreenGlassType::whereIn('EditBy', [$auth->id, 1])->get();

        $item = ScreenGlazingType::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.screen_glazing_type.edit', compact('item','screenGlassType'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'FireRating' => 'required',
            'ScreenGlassId' => 'required',
            'GlazingSystem' => 'required',
            'GlazingThickness' => 'required|numeric',
            'Beading' => 'required',
            'BeadingHeight' => 'required|numeric',
            'BeadingWidth' => 'required|numeric',
            'FixingDetails' => 'required',
            'price' => 'nullable|numeric'

        ]);

        $glazing = ScreenGlazingType::findOrFail($id);

        DB::transaction(function () use ($request, $glazing) {

            $glazing->update([

                'FireRating' => $request->FireRating,
                'ScreenGlassId' => $request->ScreenGlassId,
                'GlazingSystem' => $request->GlazingSystem,
                'GlazingThickness' => $request->GlazingThickness,
                'Beading' => $request->Beading,
                'BeadingHeight' => $request->BeadingHeight,
                'BeadingWidth' => $request->BeadingWidth,
                'FixingDetails' => $request->FixingDetails,
                'EditBy' => auth()->id()

            ]);

            if(auth()->id() != 1)
            {
                SelectedScreenGlazing::updateOrCreate(

                    [
                        'glazing_id' => $glazing->id,
                        'editBy' => auth()->id()
                    ],
                    [
                        'glazingSelectedPrice' => $request->price ?? 0
                    ]

                );
            }

        });

        return redirect()->route('Screen-Glazing-Type.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        SelectedScreenGlazing::where('id', $id)->delete();
        return back()->with('success', 'Deleted successfully');
    }

    /**
     * BULK UPDATE
     */
    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedScreenGlazing::where('editBy', $userId)
                ->whereNotIn('glazing_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedScreenGlazing::updateOrCreate(
                        [
                            'editBy'   => $userId,
                            'glazing_id' => $row['id']
                        ],
                        [
                            'glazingSelectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedScreenGlazing::where('editBy', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
