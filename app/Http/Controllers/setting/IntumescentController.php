<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Option;
use App\Models\SettingIntumescentSeals2;
use App\Models\ConfigurableItems;
use App\Models\SelectedIntumescentSeals2;


class IntumescentController extends Controller
{
    public function intumescentseals($pageId)
    {

        if(Auth::user()->UserType == 2){
            $UserId = ['1', Auth::user()->id];
        }else{
            $UserId = ['1'];
        }
        
        $intumesecentSeal = SettingIntumescentSeals2::where('configurableitems',$pageId)->wherein('editBy',$UserId)->groupBy('brand', 'intumescentSeals', 'firerating')->orderBy('id','desc')->get();
        $i = 1;
        $tbl = '';
        foreach($intumesecentSeal as $tt){
            if ($tt->editBy != 1 || Auth::user()->UserType == 1){
                $action = '<td>
                <span style="display: flex;">
                    <form action="'.route('updintumescentseals').'" method="post">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="submit" name="upd" value="'.$tt->id.'" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </button>
                    </form>
                    <form action="'.route('deleteintumescentseals').'" method="post">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="submit" name="delete" value="'.$tt->id.'" onClick="return confirm(\'Are you sure, you want to delete?\')" class="btn btn-danger" style="margin-left: 5px;">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </span>
            </td>';
            }else{
                $action = '<td></td>';
            }
            
            $tbl .=
            '
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$tt->firerating.'</td>
                    <td>'.$tt->intumescentSeals.'</td>
                    <td>'.$tt->brand.'</td>
                    <td>'.$tt->firetested.'</td>
                    <td>'.$tt->Point1height.' - '.$tt->Point2height.'</td>
                    <td>'.$tt->Point1width.' - '.$tt->Point2width.'</td>
                    '.$action.'
                </tr>
            ';
            $i++;
        }
        
        $firerating = Option::where(['configurableitems'=>$pageId,'OptionSlug'=>'fire_rating'])->get();
        $ConfigurableItems = ConfigurableItems::get();
        return view('Setting.intumescentseals',['firerating' => $firerating, 'tbl' => $tbl, 'ConfigurableItems' => $ConfigurableItems, 'pageId' => $pageId]);
    }
    
    public function submitintumescentseals(Request $request)
    {

        $valid = $request->validate([
            'configurableitems' => 'required',
            'firerating' => 'required',
            'configuration' => 'required',
            'intumescentSeals' => 'required',
            'brand' => 'required',
            'firetested' => 'required'
        ]);
        // dd($valid);
        $update_val = $request->id;
        $leafTypesArray = $request->input('leaf_type'); // This will be an array like [1, 2, 3]

            // Check if any checkboxes were selected
            if ($leafTypesArray) {
                // Convert the array into a comma-separated string
                $leafTypesString = implode(',', $leafTypesArray); // Result: "1,2,3"
            } else {
                // If no checkboxes are selected, store an empty string
                $leafTypesString = '';
            }
            
        if(!is_null($update_val)){
            $a = SettingIntumescentSeals2::find($update_val);
            $selectedOption = SelectedIntumescentSeals2::where('intumescentseals2_id',$update_val)->where('selected_intumescentseals2_user_id',Auth::user()->id)->first();
            if(empty($selectedOption)){
                $selectedOption = new SelectedIntumescentSeals2();
            }
            
            $a->configurableitems = $request->configurableitems;
            $a->firerating = $request->firerating;
            $a->tag = $request->tag;
            $a->configuration = $request->configuration;
            $a->intumescentSeals = $request->intumescentSeals;
            $a->brand = $request->brand;
            $a->firetested = $request->firetested;
            $a->Point1height = $request->Point1height;
            $a->Point2height = $request->Point2height;
            $a->Point1width = $request->Point1width;
            $a->Point2width = $request->Point2width;
            $a->customeleafTypes = $leafTypesString;
            $a->updated_at = date('Y-m-d H:i:s');
            $a->editBy = Auth::user()->id;
            $a->save();

            $selectedOption->selected_configurableitems = $a->configurableitems;
            $selectedOption->intumescentseals2_id = $a->id;
            $selectedOption->selected_intumescentseals2_user_id = Auth::user()->id;
            $selectedOption->selected_configuration = $a->configuration;
            $selectedOption->selected_doorname = $a->doorname;
            $selectedOption->selected_firerating = $a->firerating;
            $selectedOption->selected_tag = $a->tag;
            $selectedOption->selected_intumescentSeals = $a->intumescentSeals;
            $selectedOption->selected_brand = $a->brand;
            $selectedOption->selected_firetested = $a->firetested;
            $selectedOption->selected_Point1height = $a->Point1height;
            $selectedOption->selected_Point1width = $a->Point1width;
            $selectedOption->selected_Point2height = $a->Point2height;
            $selectedOption->selected_Point2width = $a->Point2width;
            $selectedOption->selected_cost = ($request->IntumescentSealPrice) ?? 0;
            $selectedOption->save();
        } else {
            // $check = SettingIntumescentSeals2::where('tag',$request->tag)->first();
            // if(!empty($check)){
            //     return redirect()->back()->with('error', 'The Intumescent Seals tag already exist!');
            // }else{
                $a = new SettingIntumescentSeals2;
                $a->created_at = date('Y-m-d H:i:s');
                $a->configurableitems = $request->configurableitems;
                $a->firerating = $request->firerating;
                $a->tag = $request->firerating;
                $a->configuration = $request->configuration;
                $a->intumescentSeals = $request->intumescentSeals;
                $a->brand = $request->brand;
                $a->firetested = $request->firetested;
                $a->Point1height = $request->Point1height;
                $a->Point2height = $request->Point2height;
                $a->Point1width = $request->Point1width;
                $a->Point2width = $request->Point2width;
                $a->customeleafTypes = $leafTypesString;
                $a->updated_at = date('Y-m-d H:i:s');
                $a->editBy = Auth::user()->id;
                $a->save();

                $selectedOption = new SelectedIntumescentSeals2();
                $selectedOption->selected_configurableitems = $a->configurableitems;
                $selectedOption->intumescentseals2_id = $a->id;
                $selectedOption->selected_intumescentseals2_user_id = Auth::user()->id;
                $selectedOption->selected_configuration = $a->configuration;
                $selectedOption->selected_doorname = $a->doorname;
                $selectedOption->selected_firerating = $a->firerating;
                $selectedOption->selected_tag = $a->tag;
                $selectedOption->selected_intumescentSeals = $a->intumescentSeals;
                $selectedOption->selected_brand = $a->brand;
                $selectedOption->selected_firetested = $a->firetested;
                $selectedOption->selected_Point1height = $a->Point1height;
                $selectedOption->selected_Point1width = $a->Point1width;
                $selectedOption->selected_Point2height = $a->Point2height;
                $selectedOption->selected_Point2width = $a->Point2width;
                $selectedOption->selected_cost = ($request->IntumescentSealPrice) ?? 0;
                $selectedOption->save();
            // }
        }


        if(!is_null($update_val)){
            return redirect('options/selected/intumescentSealArrangement')->with('success', 'The Intumescent Seals update successfully!');
        }
        else
        {
            return redirect('options/selected/intumescentSealArrangement')->with('success', 'The Intumescent Seals added successfully!');
        }
    }
    
    public function deleteintumescentseals(Request $request)
    {
        $id = $request->delete;
        $a = SettingIntumescentSeals2::where('id',$id)->first();
        $del = SettingIntumescentSeals2::where('tag',$a->tag)->limit(6)->delete();
        return redirect()->back()->with('success', 'The Intumescent Seals deleted successfully!');
    }
    
    public function updintumescentseals(Request $request)
    {
        $id = $request->upd;
        $upd = SettingIntumescentSeals2::find($id);
        return redirect()->back()->with('upd',$upd);
    }
}
