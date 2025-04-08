<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

use App\Models\ConfigurableDoorFormula;
use Session;

class ConfigurableDoorFormulaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
//        if(Auth::user()->UserType=='2'):
//            return redirect('/');
//        endif;
    }

    public function editConfigurableDoorFormula()
    {
        $ConfigurableDoorFormula = ConfigurableDoorFormula::where('status',1)->get();
        return view('Setting.ConfigurableDoorFormula',['ConfigurableDoorFormula' => $ConfigurableDoorFormula]);
    }

    public function saveConfigurableDoorFormula(Request $request){

        if(empty($request->slug)){
            return redirect()->back();
        }

        $slug = $request->slug;

        switch($slug){
            case "undercut" :
                $validator = Validator::make($request->all(), [
                    'undercut' => 'required|numeric|min:1',
                ]);
                break;
            case in_array($slug,[
                "leaf_width_1_for_single_door_set",
                "leaf_width_1_for_double_door_set",
                "leaf_width_2_for_double_door_set",
                "leaf_width_2_for_leaf_and_a_half",
                "op_width"
            ]):
                $validator = Validator::make($request->all(), [
                    'tolerance' => 'required|numeric|min:1',
                    'frame_thickness' => 'required|numeric|min:1',
                    'gap' => 'required|numeric|min:1'
                ]);
                break;

            case "frame_width" :
                $validator = Validator::make($request->all(), [
                    'tolerance' => 'required|numeric|min:1'
                ]);
                break;

            case in_array($slug,[
                "core_width_1",
                "core_width_2",
                "core_height"
            ]):
                $validator = Validator::make($request->all(), [
                    'lipping_thickness' => 'required|numeric|min:1'
                ]);
                break;
        }


        if ($validator->fails()) {
            return redirect('setting/edit-configurable-door-formula')
                ->withErrors($validator)
                ->withInput();
        }else{

            $input = $request->except(['_token','slug']);

            ConfigurableDoorFormula::where('slug', $slug)
                ->update([
                    'value' => json_encode($input),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);


            return redirect()->back()->with('success', 'Data stored successfully...');
        }

    }


}