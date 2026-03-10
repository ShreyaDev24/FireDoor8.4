<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlassGlazingSystem;
use App\Models\GlassType;
use App\Models\GlazingSystem;
use DB;

class GlassGlazingController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $auth = auth()->user();

        $items = GlazingSystem::leftJoin('selected_glazing_system', function ($join) use ($auth) {
                $join->on('glazing_system.id', '=', 'selected_glazing_system.glazingId')
                    ->where('selected_glazing_system.userId', $auth->id);
            })
            ->join('glass_glazing_system', 'glass_glazing_system.glazing_system', '=', 'glazing_system.id')
            ->whereIn('glass_glazing_system.UserId', [$auth->id, 1])
            ->select(
                'glazing_system.*',
                'selected_glazing_system.selectedPrice',
                'selected_glazing_system.id as selectedId',
                'selected_glazing_system.userId as selectedUserId',

                'glass_glazing_system.GlassType',
                'glass_glazing_system.GlazingSystem',
                'glass_glazing_system.VPAreaSize',
                'glass_glazing_system.UserId',
                'glass_glazing_system.id as mainId'
            )
            ->orderBy('glass_glazing_system.GlassType', 'ASC')
            ->orderBy('glass_glazing_system.GlazingSystem', 'ASC')
            ->get();
            // DB::transaction(function () use ($items) {

            //     foreach ($items as $val) {


            //         // if ($fire) {
            //             GlassGlazingSystem::where('id', $val->mainId)
            //                 ->update(['NFR' => $val->NFR,'FD30' => $val->FD30,'FD60' => $val->FD60]);
            //         // }
            //     }
            // });


        return view('SelectedOptions.glass_glazing_system.index', compact('items'));
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $glassTypes = GlassType::orderBy('GlassType')->get();

        $glazingSystems = GlazingSystem::orderBy('GlazingSystem')->get();

        return view('SelectedOptions.glass_glazing_system.create', compact(
            'glassTypes',
            'glazingSystems'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'core' => 'required',
            'firerating' => 'required',
            'GlassType' => 'required',
            'glazingSystem' => 'required',
            'vpareasize' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request) {

            $glassType = GlassType::find($request->GlassType);

            $glazingSystem = GlazingSystem::find($request->glazingSystem);

            GlassGlazingSystem::create([

                'ConfigurableItems' => $request->core,

                'firerating' => $request->firerating,

                'glass_id' => $request->GlassType,

                'glazing_system' => $request->glazingSystem,

                'GlassType' => $glassType->GlassType,

                'GlazingSystem' => $glazingSystem->GlazingSystem,

                'VPAreaSize' => $request->vpareasize,

                'UserId' => auth()->id(),

                'Status' => 1,

                ...$this->fireRating($request),
            ]);

        });

        return redirect()->route('Glass-Glazing-System.index')
            ->with('success', 'Created Successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $item = GlassGlazingSystem::findOrFail($id);

        $glassTypes = GlassType::orderBy('GlassType')->get();

        $glazingSystems = GlazingSystem::orderBy('GlazingSystem')->get();

        return view('SelectedOptions.glass_glazing_system.edit', compact(
            'item',
            'glassTypes',
            'glazingSystems'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'core' => 'required',
            'firerating' => 'required',
            'GlassType' => 'required',
            'glazingSystem' => 'required',
            'vpareasize' => 'required|numeric'
        ]);

        DB::transaction(function () use ($request, $id) {

            $item = GlassGlazingSystem::findOrFail($id);

             $glassType = GlassType::find($request->GlassType);

            $glazingSystem = GlazingSystem::find($request->glazingSystem);

            $item->update([

                'ConfigurableItems' => $request->core,

                'firerating' => $request->firerating,

                'glass_id' => $request->GlassType,

                'glazing_system' => $request->glazingSystem,

                'GlassType' => $glassType->GlassType,

                'GlazingSystem' => $glazingSystem->GlazingSystem,

                'VPAreaSize' => $request->vpareasize,

                'UserId' => auth()->id(),

                'Status' => 1,

                ...$this->fireRating($request),

            ]);

        });

        return redirect()->route('Glass-Glazing-System.index')
            ->with('success', 'Updated Successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        GlassGlazingSystem::where('id', $id)->delete();

        return back()->with('success', 'Deleted Successfully');
    }


    private function coreMap($request)
    {
        $core = $request->core ?? [];

        return [

            'Streboard' => in_array(1, $core) ? 1 : null,

            'Halspan' => in_array(2, $core) ? 2 : null,

            'Flamebreak' => in_array(7, $core) ? 7 : null,

            'Stredor' => in_array(8, $core) ? 8 : null,

            'NormaDoorCore' => in_array(3, $core) ? 3 : null,

            'VicaimaDoorCore' => in_array(4, $core) ? 4 : null,

            'Seadec' => in_array(5, $core) ? 5 : null,

            'Deanta' => in_array(6, $core) ? 6 : null,

            'MMM' => in_array(9, $core) ? 9 : null,

        ];
    }


    private function fireRating($request)
    {
        $rating = $request->firerating ?? [];

        return [

            'NFR' => in_array('NFR', $rating) ? 'NFR' : null,

            'FD30' => in_array('FD30', $rating) ? 'FD30' : null,

            'FD60' => in_array('FD60', $rating) ? 'FD60' : null,

        ];
    }

}
