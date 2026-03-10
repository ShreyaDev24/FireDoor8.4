<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\SelectedOption;
use Illuminate\Support\Str;
use DB;

class FinishCostController extends Controller
{

    public function index()
    {
        $auth = auth()->user();

        $items = Option::leftJoin('selected_option as so', function ($join) use ($auth) {

            $join->on('options.id', '=', 'so.optionId')
                ->where('so.SelectedUserId', $auth->id);

        })
        ->whereIn('options.editBy', [$auth->id, 1])
        ->whereIn('options.OptionSlug', ['Architrave_Finish', 'Frame_Finish', 'Door_Leaf_Facing', 'door_leaf_finish'])
        ->where(['options.is_deleted' => 0])
        ->select(
            'options.*',
            'so.id as selectedId',
            'so.SelectedOptionCost'
        )
        ->whereNotIn('options.configurableitems',[3])
        ->orderBy('options.configurableitems')
        ->orderBy('options.OptionName')
        ->orderBy('options.OptionValue')
        ->get();

        return view('SelectedOptions.finish_cost.index', compact('items','auth'));
    }


    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedOption::where('SelectedUserId', $userId)
                ->whereNotIn('optionId', $keys)
                ->delete();

            foreach ($request->rows as $row) {

            $electedOption = Option::Where('id', $row['id'])->select('id', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey','configurableitems')->first();

                if ($row['checked']) {

                    SelectedOption::updateOrCreate(

                        [
                            'SelectedUserId' => $userId,
                            'optionId' => $row['id']
                        ],

                        [
                            'SelectedOptionCost' => $row['price'] ?? 0,
                            'configurableitems' => $electedOption->configurableitems,
                            'tag' => $electedOption->OptionSlug,
                            'SelectedUnderAttribute' => $electedOption->UnderAttribute,
                            'SelectedOptionSlug' => $electedOption->OptionSlug,
                            'SelectedOptionKey' => $electedOption->OptionKey,
                            'SelectedOptionValue' => $electedOption->OptionValue,
                        ]

                    );
                }
            }

        } else {

            SelectedOption::where('SelectedUserId', $userId)->delete();

        }

        return response()->json(['status' => 'ok']);
    }


    public function exportSelected(Request $request)
    {
        $ids = $request->ids;

        $auth = auth()->user();

        $items = Option::leftJoin('selected_option as so', function ($join) use ($auth) {

            $join->on('options.id', '=', 'so.optionId')
                ->where('so.SelectedUserId', $auth->id);

        })
        ->whereIn('options.editBy', [$auth->id, 1])
        ->whereIn('options.id', $ids)
        ->whereIn('options.OptionSlug', ['Architrave_Finish', 'Frame_Finish', 'Door_Leaf_Facing', 'door_leaf_finish'])
        ->where(['options.is_deleted' => 0])
        ->select(
            'options.*',
            'so.id as selectedId',
            'so.SelectedOptionCost'
        )
        ->whereNotIn('options.configurableitems',[3])
        ->orderBy('options.OptionSlug')
        ->orderBy('options.configurableitems')
        ->orderBy('options.OptionName')
        ->orderBy('options.OptionValue')
        ->get();

        $filename = 'Options_selected_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($items) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'configurableitems',
                'Door Leaf Name',
                'Under Attribute',
                'Door Leaf',
                'Price'
            ]);

            foreach ($items as $item) {

                fputcsv($file, [
                    configurationDoor($item->configurableitems),
                    str_replace('_', ' ',$item->OptionSlug),
                    str_replace('_', ' ',$item->UnderAttribute),
                    $item->OptionValue,
                    number_format($item->SelectedOptionCost ?? 0, 2)

                ]);
            }

            fclose($file);

        }, 200, $headers);
    }

}
