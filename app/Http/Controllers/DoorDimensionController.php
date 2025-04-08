<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DoorDimension;
use App\Models\SelectedDoordimension;
use App\Models\Option;
use DB;




class DoorDimensionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $editdata=DoorDimension::get();
        $option_data = Option::where('configurableitems',3)->get();
        return view('DoorDimension.addDoorDimension' ,['editdata' => $editdata, 'option_data' => $option_data]);
    }

    public function store(request $request){
        if(property_exists($request, 'id') && $request->id !== null){
            $dimensiondata = DoorDimension::find($request->id);
            $selectedOption = SelectedDoordimension::where('doordimension_id',$request->id)->where('doordimension_user_id',Auth::user()->id)->first();
        }else{


            $dimensiondata = new DoorDimension();
            $selectedOption = new SelectedDoordimension();
        }
        
        $doorCore = doorcorename($request->configurableitems);
        if ($request->configurableitems == 3 && $request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $filepath = public_path('DoorDimension');
            $file->move($filepath,$name);
            $dimensiondata->image = $name;
        }

        // if($request->configurableitems == 1 || $request->configurableitems == 2){
        //     $dimensiondata->configurableitems=$request->configurableitems;
        //     // $dimensiondata->code=$request->code;
        //     $dimensiondata->mm_height=$request->mm_height;
        //     $dimensiondata->mm_width=$request->mm_width;
        //     $dimensiondata->fire_rating=$request->fire_rating;
        //     // $dimensiondata->selling_price=$request->selling_price;
        // }else{
            $dimensiondata->configurableitems = $request->configurableitems;
            $dimensiondata->code=$request->code;
            $dimensiondata->inch_height=$request->inch_height;
            $dimensiondata->inch_width=$request->inch_width;
            $dimensiondata->mm_height=$request->mm_height;
            $dimensiondata->mm_width=$request->mm_width;
            $dimensiondata->fire_rating=$request->fire_rating;
            $dimensiondata->door_leaf_finish=$request->door_leaf_finish;
            $dimensiondata->door_leaf_facing=$request->door_leaf_facing;
            $dimensiondata->cost_price=$request->cost_price;
            $dimensiondata->selling_price=$request->selling_price;
            $dimensiondata->leaf_type=$request->leaf_type;
        // }
        $dimensiondata->UserId = Auth::user()->id;
        $dimensiondata->editBy = Auth::user()->id;
        $dimensiondata->save();

        if(Auth::user()->id != 1){ //admin will not add selected price
            $selectedOption->selected_configurableitems = $dimensiondata->configurableitems;
            $selectedOption->doordimension_id = $dimensiondata->id;
            $selectedOption->doordimension_user_id = Auth::user()->id;
            $selectedOption->selected_firerating = $dimensiondata->fire_rating;
            $selectedOption->selected_code = $dimensiondata->code;
            $selectedOption->selected_mm_height = $dimensiondata->mm_height;
            $selectedOption->selected_mm_width = $dimensiondata->mm_width;
            $selectedOption->selected_sellingprice = $dimensiondata->selling_price;
            $selectedOption->selected_cost = ($request->DoorDimensionPrice) ?? 0;
            $selectedOption->save();
        }

    $request->session()->flash('success','Door Dimension Added Successfully');
    if(Auth::user()->UserType == 2){
        return redirect()->route('options/selected', ['door_dimension']);

    }else{
        return redirect()->route('options/selected', ['door_dimension']);

    }

    }
    
    public function storeCustome(request $request){
    // dd($request->all());
        if(property_exists($request, 'id') && $request->id !== null){
            $dimensiondata = DoorDimension::find($request->id);
            $selectedOption = SelectedDoordimension::where('doordimension_id',$request->id)->where('doordimension_user_id',Auth::user()->id)->first();
        }else{
            $dimensiondata = new DoorDimension();
            $selectedOption = new SelectedDoordimension();
        }
        
        $prices = $request->input('prices');
        $pricesJson = json_encode($prices);
        $dimensiondata->configurableitems = $request->configurableitems;
        $dimensiondata->code=$request->code;
        $dimensiondata->inch_height=$request->inch_height;
        $dimensiondata->inch_width=$request->inch_width;
        $dimensiondata->mm_height=$request->mm_height;
        $dimensiondata->mm_width=$request->mm_width;
        $dimensiondata->fire_rating=$request->fire_rating;
        $dimensiondata->door_leaf_finish=$request->door_leaf_finish;
        $dimensiondata->door_leaf_facing=$request->door_leaf_facing;
        $dimensiondata->cost_price=$request->cost_price;
        $dimensiondata->selling_price=$request->selling_price;
        $dimensiondata->leaf_type=$request->leaf_type;
        $dimensiondata->UserId = Auth::user()->id;
        $dimensiondata->editBy = Auth::user()->id;
        $dimensiondata->save();

        if(Auth::user()->id != 1){ //admin will not add selected price
            $selectedOption->selected_configurableitems = $dimensiondata->configurableitems;
            $selectedOption->doordimension_id = $dimensiondata->id;
            $selectedOption->doordimension_user_id = Auth::user()->id;
            $selectedOption->selected_firerating = $dimensiondata->fire_rating;
            $selectedOption->selected_code = $dimensiondata->code;
            $selectedOption->selected_mm_height = $dimensiondata->mm_height;
            $selectedOption->selected_mm_width = $dimensiondata->mm_width;
            $selectedOption->selected_sellingprice = $dimensiondata->selling_price;
            $selectedOption->selected_cost = ($request->DoorDimensionPrice) ?? 0;
            $selectedOption->custome_door_selected_cost =$pricesJson ?? 0;
            $selectedOption->save();
        }

    $request->session()->flash('success','Door Dimension Added Successfully');
    if(Auth::user()->UserType == 2){
        return redirect()->route('options/selected', ['door_dimension_custome']);

    }else{
        return redirect()->route('options/selected', ['door_dimension_custome']);

    }

    }

    public function list($pageId){
        if(Auth::user()->UserType == 2){
            $UserId = ['1', Auth::user()->id];
        }else{
            $UserId = ['1'];
        }
        
        $doorDimension=DoorDimension::where('is_deleted', 0)->wherein('editBy',$UserId)->where('configurableitems',$pageId)->orderBy('id', 'DESC')->get();
        return view('DoorDimension.DoorDimensionList',['doorDimension' => $doorDimension, 'pageId' => $pageId]);
    }

    public function edit($id){
        $editdata=DoorDimension::where('id',$id)->first();
        $option_data = Option::where('configurableitems',3)->get();
        return view('DoorDimension.addDoorDimension',['editdata' => $editdata, 'option_data' => $option_data]);

    }

    public function dimension_delete(request $request){
        $id=$request->id;
        $doorCore = doorcorename($request->doorCore);
        $doorDimension = DoorDimension::where('id',$request->id)->first();
        $doorDimension->is_deleted = 1;
        $doorDimension->save();
        if(Auth::user()->id == 1){
            SelectedDoordimension::where('doordimension_id', $request->id)->delete();
        }else{
            SelectedDoordimension::where('doordimension_id', $request->id)->where('doordimension_user_id', Auth::user()->id)->delete();
        }

        if(Auth::user()->UserType == 2){
            return redirect()->route('options/selected', ['door_dimension']);
        }else{
            return redirect()->route('options/selected', ['door_dimension']);
        }

    }


    public function filterDoorleafFacingValue(request $request){
        $data = Option::where('configurableitems',3)->where('UnderAttribute',$request->doorLeafFacing)->where('OptionSlug','door_leaf_finish')->get();
        return $data;
    }
}
