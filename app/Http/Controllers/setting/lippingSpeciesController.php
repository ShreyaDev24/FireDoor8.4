<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;



class lippingSpeciesController extends Controller
{
    public function lippingSpecies()
    {
        if(Auth::user()->UserType == 2){
            $UserId = ['1', Auth::user()->id];
        }else{
            $UserId = ['1'];
        }

        $ls = LippingSpecies::with('lipping_species_items')->wherein('editBy',$UserId)->where('Status',1)->orderBy('id','desc')->orderBy('id','desc')->get();

        return view('Setting.lippingSpecies',['ls' => $ls]);
    }

    public function sublippingSpecies(Request $request)
    {

        $valid = $request->validate([
            'SpeciesName' => 'required',
            'MinValue' => 'required',
            'MaxValues' => 'required',
        ],[
            'SpeciesName.required' => 'The species name field is required.',
            'MinValue.required' => 'The minimum value field is required.',
            'MaxValues.required' => 'The maximum value field is required.',
        ]);
        $update_val = $request->updval;
        if(!is_null($update_val)){
            $a = LippingSpecies::find($update_val);
        } else {
            $a = new LippingSpecies;
            $a->created_at = date('Y-m-d H:i:s');
        }

        $image = $request->file;
        if(!empty($image)){
            $valid = $request->validate([
                'file' => 'mimes:jpeg,jpg,png,gif|max:10000'
            ]);
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $a->file = $imageName;
            $image->move(public_path('uploads/Options'), $imageName);
        }

        $a->SpeciesName = $request->SpeciesName;
        $a->MinValue = $request->MinValue;
        $a->MaxValues = $request->MaxValues;
        $a->editBy = Auth::user()->id;
        $a->updated_at = date('Y-m-d H:i:s');
        // $a->save();

        if($a->save()){
            LippingSpeciesItems::where(['lipping_species_id' => $a->id])->delete();
            foreach ($request->Price as $key => $priceVal) {

                $LippingSpeciesItems = new LippingSpeciesItems;
                $LippingSpeciesItems->lipping_species_id = $a->id;
                $LippingSpeciesItems->thickness = $request->Thickness[$key];
                $LippingSpeciesItems->price = $priceVal;
                $LippingSpeciesItems->status = 1;
                $LippingSpeciesItems->save();

            }


            if(!is_null($update_val)){
                return redirect()->back()->with('success', 'The Lipping Species update successfully!');
            }
            else
            {
                return redirect()->back()->with('success', 'The Lipping Species added successfully!');
            }
        }

        return null;


    }

    public function deletelippingSpecies(Request $request)
    {
        $id = $request->delete;
        $LippingSpecies = LippingSpecies::find($id);
        $LippingSpecies->Status = 0;
        $LippingSpecies->save();
        // $LippingSpeciesItems = LippingSpeciesItems::where('lipping_species_id',$id)->delete();
        return redirect()->back()->with('success', 'The Lipping Species deleted successfully!');
    }

    public function updlippingSpecies(Request $request)
    {
        $id = $request->upd;
        $ls = LippingSpecies::with('lipping_species_items')->find($id);
        return redirect()->back()->with('upd',$ls);
    }
}
