<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpeciesItems;
use Illuminate\Support\Facades\Auth;

class LippingSpeciesController extends Controller
{

    public function index()
    {

        $auth = Auth::user();

        $userIds = $auth->UserType == 2 ? [1, $auth->id] : [1];

        $species = LippingSpecies::with([
            'lipping_species_items' => function ($q) use ($auth) {
                $q->where('thickness', '<=', 4)
                ->with(['selected_lipping_species_items' => function ($q2) use ($auth) {
                    $q2->where('selected_user_id', $auth->id);
                }]);
            }
        ])
        ->where('Status', 1)
        ->whereIn('editBy', $userIds)
        ->orderBy('SpeciesName', 'ASC')
        ->get();

        return view('SelectedOptions.lipping_species.index',compact('species', 'auth'));

    }

    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedLippingSpeciesItems::where('selected_user_id', $userId)
                ->whereNotIn('selected_lipping_species_items_id', $keys)
                ->delete();

            foreach ($request->rows as $row) {

            $electedOption = LippingSpeciesItems::Where('id', $row['id'])->select('*')->first();

                if ($row['checked']) {

                    SelectedLippingSpeciesItems::updateOrCreate(

                        [
                            'selected_user_id' => $userId,
                            'selected_lipping_species_items_id' => $row['id']
                        ],

                        [
                            'selected_price' => $row['price'] ?? 0,
                            'selected_lipping_species_id' => $electedOption->lipping_species_id,
                            'selected_thickness' => $electedOption->thickness,
                            'selected_status' => $electedOption->status,
                        ]

                    );
                }
            }

        } else {

            SelectedLippingSpeciesItems::where('selected_user_id', $userId)->delete();

        }

        return response()->json(['status' => 'ok']);
    }

    public function exportSelected(Request $request)
    {
        $ids = $request->ids;

        $auth = auth()->user();

        $userIds = $auth->UserType == 2 ? [1, $auth->id] : [1];

        $items = LippingSpecies::with([
            'lipping_species_items' => function ($q) use ($auth, $ids) {
                $q->whereIn('id', $ids)
                ->where('thickness', '<=', 4)
                ->with(['selected_lipping_species_items' => function ($q2) use ($auth) {
                    $q2->where('selected_user_id', $auth->id);
                }]);
            }
        ])
        ->where('Status', 1)
        ->whereIn('editBy', $userIds)
        ->orderBy('SpeciesName', 'ASC')
        ->get();

        $filename = 'Timber_Species_Selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');

            // CSV Heading
            fputcsv($file, [
                'Species',
                'Inch',
                'MM',
                'Status',
                'Price / M3'
            ]);

            foreach ($items as $row) {

                foreach ($row->lipping_species_items as $item) {

                    $selected = $item->selected_lipping_species_items->first();

                    if ($selected) {

                        fputcsv($file, [
                            $row->SpeciesName,
                            $item->thickness,
                            number_format($item->thickness * 25.4, 1),
                            $item->status ? 'Active' : 'Inactive',
                            $selected->selected_price ?? 0
                        ]);

                    }

                }

            }

            fclose($file);

        }, 200, $headers);
    }
}
