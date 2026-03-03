<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OverpanelGlassGlazing;
use App\Models\SelectedOverpanelGlassGlazing;
use Illuminate\Support\Str;
use DB;

class OverpanelGlassGlazingType extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $items = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing as sogg', function ($join) use ($auth) {
                $join->on('overpanel_glass_glazing.id', '=', 'sogg.glass_glazing_id')
                     ->where('sogg.editBy', $auth->id);
            })
            ->whereIn('overpanel_glass_glazing.editBy', [$auth->id, 1])
            ->select(
                'overpanel_glass_glazing.*',
                'sogg.id as selectedId',
                'sogg.glassSelectedPrice',
                'sogg.glazingSelectedPrice'
            )
            ->orderBy('overpanel_glass_glazing.GlassType')
            ->get();

        return view('SelectedOptions.overpanel_glass_glazing.index', compact('items', 'auth'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('SelectedOptions.overpanel_glass_glazing.create');
    }

    /**
     * STORE
     */

   public function store(Request $request)
    {
        $validated = $request->validate([
            'core'              => 'required',

            'firerating'        => 'required',

            'GlassIntegrity'    => 'required|in:Integrity_And_Insulation,Integrity_only',
            'GlassType'         => 'required|string|max:255',
            'GlassThickness'    => 'required|numeric|min:0',

            'FanLightWidth'     => 'required|numeric|min:0',
            'FanLightHeight'    => 'required|numeric|min:0',
            'SideScreenWidth'   => 'required|numeric|min:0',
            'SideScreenHeight'  => 'required|numeric|min:0',

            'TransomThickness'  => 'required|numeric|min:0',
            'TransomDepth'      => 'required|numeric|min:0',

            'GlazingSystem'     => 'required|string|max:255',
            'GlazingThickness'  => 'required|numeric|min:0',
            'Beading'           => 'required|numeric|min:0',
            'BeadingHeight'     => 'required|numeric|min:0',
            'BeadingWidth'      => 'required|numeric|min:0',
            'FixingDetails'     => 'required|string|max:255',

            'glass_price'       => 'nullable|numeric|min:0',
            'glazing_price'     => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {

            $slug = Str::slug($validated['GlassType'], '_');

            $item = OverpanelGlassGlazing::create([
                'Key'              => $slug,
                'GlassIntegrity'   => $validated['GlassIntegrity'],
                'GlassType'        => $validated['GlassType'],
                'GlassThickness'   => $validated['GlassThickness'],

                'FanLightWidth'    => $validated['FanLightWidth'],
                'FanLightHeight'   => $validated['FanLightHeight'],
                'SideScreenWidth'  => $validated['SideScreenWidth'],
                'SideScreenHeight' => $validated['SideScreenHeight'],

                'TransomThickness' => $validated['TransomThickness'],
                'TransomDepth'     => $validated['TransomDepth'],

                'GlazingSystem'    => $validated['GlazingSystem'],
                'GlazingThickness' => $validated['GlazingThickness'],
                'Beading'          => $validated['Beading'],
                'BeadingHeight'    => $validated['BeadingHeight'],
                'BeadingWidth'     => $validated['BeadingWidth'],
                'FixingDetails'    => $validated['FixingDetails'],
                'editBy' => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1) {
                SelectedOverpanelGlassGlazing::create([
                    'glass_glazing_id'      => $item->id,
                    'editBy'               => auth()->id(),
                    'glassSelectedPrice'   => $request->glass_price ?? 0,
                    'glazingSelectedPrice' => $request->glazing_price ?? 0,
                ]);
            }
        });

        return redirect()->route('Overpanel-Glass-Type.index')
            ->with('success', 'Overpanel Glass Glazing added successfully');
    }

     /**
     * EDIT
     */
    public function edit($id)
    {
        $item = OverpanelGlassGlazing::with([
            'selectedPrice' => function ($q) {
                $q->where('editBy', auth()->id());
            }
        ])->findOrFail($id);

        return view('SelectedOptions.overpanel_glass_glazing.edit', compact('item'));
    }

    /**
     * UPDATE
     */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'core'              => 'required',
            'firerating'        => 'required',

            'GlassIntegrity'    => 'required|in:Integrity_And_Insulation,Integrity_only',
            'GlassType'         => 'required|string|max:255',
            'GlassThickness'    => 'required|numeric|min:0',

            'FanLightWidth'     => 'required|numeric|min:0',
            'FanLightHeight'    => 'required|numeric|min:0',
            'SideScreenWidth'   => 'required|numeric|min:0',
            'SideScreenHeight'  => 'required|numeric|min:0',

            'TransomThickness'  => 'required|numeric|min:0',
            'TransomDepth'      => 'required|numeric|min:0',

            'GlazingSystem'     => 'required|string|max:255',
            'GlazingThickness'  => 'required|numeric|min:0',
            'Beading'           => 'required|numeric|min:0',
            'BeadingHeight'     => 'required|numeric|min:0',
            'BeadingWidth'      => 'required|numeric|min:0',
            'FixingDetails'     => 'required|string|max:255',

            'glass_price'       => 'nullable|numeric|min:0',
            'glazing_price'     => 'nullable|numeric|min:0',
        ]);

        $item = OverpanelGlassGlazing::findOrFail($id);

        DB::transaction(function () use ($item, $validated, $request) {

            $slug = Str::slug($validated['GlassType'], '_');

            $item->update([
                'Key'              => $slug,
                'GlassIntegrity'   => $validated['GlassIntegrity'],
                'GlassType'        => $validated['GlassType'],
                'GlassThickness'   => $validated['GlassThickness'],

                'FanLightWidth'    => $validated['FanLightWidth'],
                'FanLightHeight'   => $validated['FanLightHeight'],
                'SideScreenWidth'  => $validated['SideScreenWidth'],
                'SideScreenHeight' => $validated['SideScreenHeight'],

                'TransomThickness' => $validated['TransomThickness'],
                'TransomDepth'     => $validated['TransomDepth'],

                'GlazingSystem'    => $validated['GlazingSystem'],
                'GlazingThickness' => $validated['GlazingThickness'],
                'Beading'          => $validated['Beading'],
                'BeadingHeight'    => $validated['BeadingHeight'],
                'BeadingWidth'     => $validated['BeadingWidth'],
                'FixingDetails'    => $validated['FixingDetails'],
                'editBy' => auth()->id(),
                ...$this->coreMap($request),
            ]);

            if (auth()->id() != 1 && $item->selectedPrice) {
                $item->selectedPrice->update([
                    'glassSelectedPrice'   => $request->glass_price ?? 0,
                    'glazingSelectedPrice' => $request->glazing_price ?? 0,
                ]);
            }
        });

        return redirect()->route('Overpanel-Glass-Type.index')
            ->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        OverpanelGlassGlazing::where('id', $id)->delete();
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

            SelectedOverpanelGlassGlazing::where('editBy', $userId)
                ->whereNotIn('glass_glazing_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {
                if ($row['checked']) {
                    SelectedOverpanelGlassGlazing::updateOrCreate(
                    [
                        'editBy' => $userId,
                        'glass_glazing_id' => $row['id']
                    ],
                    [
                        'glassSelectedPrice' => $row['price'] ?? 0,
                        'status' => 1
                    ]
                );

                }
            }
        }else{
            SelectedOverpanelGlassGlazing::where('editBy', $userId)->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    private function coreMap(Request $request)
    {
        $cores = $request->input('core', []);
        $fire  = $request->input('firerating', []);

        return [
            'Streboard'  => in_array(1, $cores) ? 1 : null,
            'Halspan'    => in_array(2, $cores) ? 2 : null,
            'Flamebreak' => in_array(7, $cores) ? 7 : null,
            'Stredor'    => in_array(8, $cores) ? 8 : null,

            'NFR'        => in_array('NFR', $fire) ? 'NFR' : null,
            'FD30'       => in_array('FD30', $fire) ? 'FD30' : null,
            'FD60'       => in_array('FD60', $fire) ? 'FD60' : null,
        ];
    }

    public function exportSelected(Request $request)
    {
        $ids = $request->has('ids')
            ? (is_array($request->ids) ? $request->ids : json_decode($request->ids, true))
            : [];

        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Invalid export data.');
        }

        $auth = auth()->user();

        $items = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing as sogg', function ($join) use ($auth) {
                $join->on('overpanel_glass_glazing.id', '=', 'sogg.glass_glazing_id')
                     ->where('sogg.editBy', $auth->id);
            })
            ->whereIn('overpanel_glass_glazing.editBy', [$auth->id, 1])
            ->whereIn('overpanel_glass_glazing.id', $ids)
            ->select(
                'overpanel_glass_glazing.*',
                'sogg.id as selectedId',
                'sogg.glassSelectedPrice',
                'sogg.glazingSelectedPrice'
            )
            ->orderBy('overpanel_glass_glazing.GlassType')
            ->get();

        $filename = 'Glass_Glazing_System_selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');
            fputcsv($file, ['Streboard', 'Halspan', 'Flamebreak', 'Stredor', 'NFR','FD30','FD60',
            'Integrity',
            'Glass Name',
            'Glass Thickness',
            'Max Width(FL)',
            'Max Height(Fl)',
            'Side Sceen Width',
            'Side Sceen Height',
            'MIN Fan Light/ Over Panel Frame Thickness',
            'MIN Fan Light/ Over Panel Frame Depth',
            'Glazing System',
            'Glazing Thickness',
            'Beading',
            'Beading Height',
            'Beading Width',
            'Glazing Bead Fixing Detail',
            'Glass Price Per m²',
            'Glazing Price Per L/M']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->Streboard ? 'Yes' : 'No',
                    $item->Halspan ? 'Yes' : 'No',
                    $item->Flamebreak ? 'Yes' : 'No',
                    $item->Stredor ? 'Yes' : 'No',
                    $item->NFR ?? '-',
                    $item->FD30 ?? '-',
                    $item->FD60 ?? '-',
                    str_replace('_', ' ', $item->GlassIntegrity),
                    $item->GlassType,
                    $item->GlassThickness,
                    $item->FanLightWidth,
                    $item->FanLightHeight,
                    $item->SideScreenWidth,
                    $item->SideScreenHeight,
                    $item->TransomThickness,
                    $item->TransomDepth,
                    $item->GlazingSystem,
                    $item->GlazingThickness,
                    $item->Beading,
                    $item->BeadingHeight,
                    $item->BeadingWidth,
                    $item->FixingDetails,

                    number_format($item->glassSelectedPrice ?? 0, 2),
                    number_format($item->glazingSelectedPrice ?? 0, 2),
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}
