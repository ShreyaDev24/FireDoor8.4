<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingIntumescentSeals2;
use App\Models\SelectedIntumescentSeals2;
use DB;

class IntumescentSealArrangementController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $items = SettingIntumescentSeals2::leftJoin('selected_intumescentseals2 as s', function ($join) use ($auth) {
                $join->on('setting_intumescentseals2.id', '=', 's.intumescentseals2_id')
                     ->where('s.selected_intumescentseals2_user_id', $auth->id);
            })
            ->whereIn('setting_intumescentseals2.editBy', [$auth->id, 1])
            ->select(
                'setting_intumescentseals2.*',
                's.id as selectedId',
                's.selected_cost'
            )
            ->orderBy('setting_intumescentseals2.firerating', 'ASC')
            ->orderBy('setting_intumescentseals2.brand', 'ASC')
            ->orderBy('setting_intumescentseals2.intumescentSeals', 'ASC')
            ->get();

        $items = $items->map(function ($item) {
            $leafTypeIds = explode(',', (string) $item->customeleafTypes);

            $leafTypes = DB::table('intumescent_seal_leaf_type')
                ->whereIn('id', $leafTypeIds)
                ->pluck('leaf_type_key')
                ->toArray();

            $item->leaf_type_keys = implode(', ', $leafTypes);

            return $item;
        });

        return view('SelectedOptions.intumescent_seals.index', compact('items','auth'));
    }

    public function create()
    {
        $UserId = ['1'];
        $auth = auth()->user();

        if ($auth->UserType == 2) {
            $UserId = array_merge($UserId, CompanyUsers());
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();

        return view('SelectedOptions.intumescent_seals.create', compact('IntumescentSealsConfiguration'));
    }

    public function createStandard()
    {
        $UserId = ['1'];
        $auth = auth()->user();

        if ($auth->UserType == 2) {
            $UserId = array_merge($UserId, CompanyUsers());
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();

        return view('SelectedOptions.intumescent_seals.createStandard', compact('IntumescentSealsConfiguration'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'configurableitems'   => 'required',
            'firerating'          => 'required',
            'configuration'       => 'required',
            'intumescentSeals'    => 'required',
            'brand'               => 'required',
            'FireOnly'            => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $leafTypesString = is_array($request->customeleafTypes)
                ? implode(',', $request->customeleafTypes)
                : $request->customeleafTypes;

            foreach ($request->FireOnly as $fireOnlyValue) {

                $seal = SettingIntumescentSeals2::create([
                    'configurableitems' => $request->configurableitems,
                    'firerating'        => $request->firerating,
                    'tag'               => $request->firerating,
                    'configuration'     => $request->configuration,
                    'intumescentSeals' => $request->intumescentSeals,
                    'brand'             => $request->brand,
                    'firetested'        => $request->firetested,
                    'Point1height'      => $request->Point1height,
                    'Point2height'      => $request->Point2height,
                    'Point1width'       => $request->Point1width,
                    'Point2width'       => $request->Point2width,
                    'MeetingEdges'       => $request->MeetingEdges,
                    'FireOnly'          => $fireOnlyValue,
                    'customeleafTypes'   => $leafTypesString,
                    'editBy'            => auth()->id(),
                ]);

                SelectedIntumescentSeals2::create([
                    'selected_configurableitems'           => $seal->configurableitems,
                    'intumescentseals2_id'                 => $seal->id,
                    'selected_intumescentseals2_user_id'   => auth()->id(),  // 👈 this MUST exist in $fillable
                    'selected_configuration'               => $seal->configuration,
                    'selected_doorname'                    => $seal->doorname,
                    'selected_firerating'                  => $seal->firerating,
                    'selected_tag'                         => $seal->tag,
                    'selected_intumescentSeals'            => $seal->intumescentSeals,
                    'selected_brand'                       => $seal->brand,
                    'selected_firetested'                  => $seal->firetested,
                    'selected_Point1height'                => $seal->Point1height,
                    'selected_Point1width'                 => $seal->Point1width,
                    'selected_Point2height'                => $seal->Point2height,
                    'selected_Point2width'                 => $seal->Point2width,
                    'MeetingEdges'                         => $seal->MeetingEdges,
                    'selected_cost'                        => $request->IntumescentSealPrice ?? 0,
                ]);

            }
        });

        return redirect()->route('Intumescent-Seal-Arrangement.index')
            ->with('success', 'Intumescent seal(s) created successfully');
    }

    public function edit($id)
    {
        $item = SettingIntumescentSeals2::with([
            'selected_cost' => function ($q) {
                $q->where('selected_intumescentseals2_user_id', auth()->id());
            }
        ])->findOrFail($id);

        $UserId = ['1'];
        $auth = auth()->user();

        if ($auth->UserType == 2) {
            $UserId = array_merge($UserId, CompanyUsers());
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();

        return view('SelectedOptions.intumescent_seals.edit', compact('item','IntumescentSealsConfiguration'));
    }

    public function editStandard($id)
    {
        $item = SettingIntumescentSeals2::with([
            'selected_cost' => function ($q) {
                $q->where('selected_intumescentseals2_user_id', auth()->id());
            }
        ])->findOrFail($id);

        $UserId = ['1'];
        $auth = auth()->user();

        if ($auth->UserType == 2) {
            $UserId = array_merge($UserId, CompanyUsers());
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();


        return view('SelectedOptions.intumescent_seals.editStandard', compact('item', 'IntumescentSealsConfiguration'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'configurableitems'    => 'required',
            'firerating'           => 'required',
            'configuration'        => 'required',
            'intumescentSeals'     => 'required',
            'brand'                => 'required',
            'FireOnly'             => 'required',
            'IntumescentSealPrice' => 'nullable|numeric|min:0',
        ]);


        $seal = SettingIntumescentSeals2::findOrFail($id);

        DB::transaction(function () use ($request, $seal) {

            $leafTypesString = is_array($request->customeleafTypes)
                ? implode(',', $request->customeleafTypes)
                : $request->customeleafTypes;


            $seal->update([
                'configurableitems' => $request->configurableitems,
                'firerating'        => $request->firerating,
                'tag'               => $request->firerating,
                'configuration'     => $request->configuration,
                'intumescentSeals' => $request->intumescentSeals,
                'brand'             => $request->brand,
                'firetested'        => $request->firetested,
                'Point1height'      => $request->Point1height,
                'Point2height'      => $request->Point2height,
                'Point1width'       => $request->Point1width,
                'Point2width'       => $request->Point2width,
                'MeetingEdges'       => $request->MeetingEdges,
                'FireOnly'          => $request->FireOnly,
                'customeleafTypes'   => $leafTypesString,
                'editBy'            => auth()->id(),
            ]);

            if (auth()->id() != 1) {

                SelectedIntumescentSeals2::updateOrCreate(
                    [
                        'intumescentseals2_id'                 => $seal->id,
                        'selected_intumescentseals2_user_id'   => auth()->id(),  // 👈 this MUST exist in $fillable
                    ],
                    [
                        'selected_configurableitems'           => $seal->configurableitems,
                        'selected_configuration'               => $seal->configuration,
                        'selected_doorname'                    => $seal->doorname,
                        'selected_firerating'                  => $seal->firerating,
                        'selected_tag'                         => $seal->tag,
                        'selected_intumescentSeals'            => $seal->intumescentSeals,
                        'selected_brand'                       => $seal->brand,
                        'selected_firetested'                  => $seal->firetested,
                        'selected_Point1height'                => $seal->Point1height,
                        'selected_Point1width'                 => $seal->Point1width,
                        'selected_Point2height'                => $seal->Point2height,
                        'selected_Point2width'                 => $seal->Point2width,
                        'MeetingEdges'                         => $seal->MeetingEdges,
                        'selected_cost'                        => $request->selected_cost ?? 0,
                    ]
                );
            }
        });

        return redirect()->route('Intumescent-Seal-Arrangement.index')
            ->with('success', 'Updated successfully');
    }

    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();


        SelectedIntumescentSeals2::where('selected_intumescentseals2_user_id', $userId)
            ->whereNotIn('intumescentseals2_id', $keys)
            ->delete();


        foreach ($request->rows as $row) {

            if ($row['checked']) {

                SelectedIntumescentSeals2::updateOrCreate(

                    [
                        'selected_intumescentseals2_user_id' => $userId,
                        'intumescentseals2_id' => $row['id']
                    ],

                    [
                        'selected_cost' => $row['price'] ?? 0
                    ]

                );
            }
        }

        return response()->json(['status' => 'ok']);
    }


    public function destroy($id)
    {
        SettingIntumescentSeals2::where('id', $id)->delete();
        return back()->with('success', 'Deleted successfully');
    }
}
