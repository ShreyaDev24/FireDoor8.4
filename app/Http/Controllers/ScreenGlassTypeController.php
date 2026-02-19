<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScreenGlassType;
use App\Models\SelectedScreenGlass;
use DB;

class ScreenGlassTypeController extends Controller
{
    /**
     * INDEX
     */
    public function index()
    {
        $auth = auth()->user();

        $items = ScreenGlassType::leftJoin('selected_screen_glass as sg', function ($join) use ($auth) {
                $join->on('screen_glass_type.id', '=', 'sg.glass_id')
                     ->where('sg.editBy', $auth->id);
            })
            ->whereIn('screen_glass_type.EditBy', [$auth->id, 1])
            ->select(
                'screen_glass_type.*',
                'sg.id as selectedId',
                'sg.glassSelectedPrice'
            )
            ->orderBy('screen_glass_type.GlassType')
            ->get();

        return view('SelectedOptions.screen_glass_type.index', compact('items', 'auth'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('SelectedOptions.screen_glass_type.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'FireRating'        => 'required|string',
            'DFRating'          => 'required|string',
            'GlassType'         => 'required|string',
            'WidthPoint1'       => 'required|numeric|min:0',
            'HeightPoint1'      => 'required|numeric|min:0',
            'WidthPoint2'       => 'required|numeric|min:0',
            'HeightPoint2'      => 'required|numeric|min:0',
            'TransomThickness'  => 'required|numeric|min:0',
            'TransomDepth'      => 'required|numeric|min:0',
            'AreaSize'          => 'required|numeric|min:0',
            'FrameDensity'      => 'required|numeric|min:0',
            'price'             => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $glass = ScreenGlassType::create([
                'FireRating'       => $request->FireRating,
                'GlassIntegrity'   => $request->GlassIntegrity,
                'GlassType'        => $request->GlassType,
                'DFRating'         => $request->DFRating,
                'HeightPoint1'     => $request->HeightPoint1,
                'HeightPoint2'     => $request->HeightPoint2,
                'WidthPoint1'      => $request->WidthPoint1,
                'WidthPoint2'      => $request->WidthPoint2,
                'TransomThickness'=> $request->TransomThickness,
                'TransomDepth'    => $request->TransomDepth,
                'AreaSize'         => $request->AreaSize,
                'FrameDensity'         => $request->FrameDensity,
                'EditBy'           => auth()->id(),
            ]);

            if (auth()->id() != 1) {
                SelectedScreenGlass::create([
                    'glass_id'           => $glass->id,
                    'editBy'             => auth()->id(),
                    'glassSelectedPrice' => $request->price ?? 0,
                ]);
            }
        });

        return redirect()->route('Screen-Glass-Type.index')
            ->with('success', 'Screen Glass Type added successfully');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $item = ScreenGlassType::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.screen_glass_type.edit', compact('item'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'FireRating'        => 'required|string',
            'DFRating'          => 'required|string',
            'GlassType'         => 'required|string',
            'WidthPoint1'       => 'required|numeric|min:0',
            'HeightPoint1'      => 'required|numeric|min:0',
            'WidthPoint2'       => 'required|numeric|min:0',
            'HeightPoint2'      => 'required|numeric|min:0',
            'TransomThickness'  => 'required|numeric|min:0',
            'TransomDepth'      => 'required|numeric|min:0',
            'AreaSize'          => 'required|numeric|min:0',
            'FrameDensity'      => 'required|numeric|min:0',
            'price'             => 'nullable|numeric|min:0',
        ]);


        $glass = ScreenGlassType::findOrFail($id);

        DB::transaction(function () use ($request, $glass) {

            $glass->update([
                'FireRating'       => $request->FireRating,
                'GlassIntegrity'   => $request->GlassIntegrity,
                'GlassType'        => $request->GlassType,
                'DFRating'         => $request->DFRating,
                'HeightPoint1'     => $request->HeightPoint1,
                'HeightPoint2'     => $request->HeightPoint2,
                'WidthPoint1'      => $request->WidthPoint1,
                'WidthPoint2'      => $request->WidthPoint2,
                'TransomThickness'=> $request->TransomThickness,
                'TransomDepth'    => $request->TransomDepth,
                'AreaSize'         => $request->AreaSize,
                'FrameDensity'         => $request->FrameDensity,
                'EditBy'           => auth()->id(),
            ]);

            if (auth()->id() != 1) {
                SelectedScreenGlass::updateOrCreate(
                    [
                        'glass_id' => $glass->id,
                        'editBy'   => auth()->id(),
                    ],
                    [
                        'glassSelectedPrice' => $request->price ?? 0,
                    ]
                );
            }
        });

        return redirect()->route('Screen-Glass-Type.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        ScreenGlassType::where('id', $id)->delete();
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

            SelectedScreenGlass::where('editBy', $userId)
                ->whereNotIn('glass_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedScreenGlass::updateOrCreate(
                        [
                            'editBy'   => $userId,
                            'glass_id' => $row['id']
                        ],
                        [
                            'glassSelectedPrice' => $row['price'] ?? 0
                        ]
                    );
                }
            }

        } else {
            SelectedScreenGlass::where('editBy', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
