<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\Option;
use App\Models\Color;
use App\Models\SelectedOption;
use App\Models\SelectedColor;
use App\Models\ConfigurableItems;
use Session;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function createColor()
    {
        $slug = "Door_Leaf_Facing";
        // $ConfigurableItems = ConfigurableItems::get();
        $doorLeafFacing = Option::distinct()->where(['OptionSlug' => $slug])->get(['OptionKey','OptionValue']);
        // $doorLeafFacing = Option::where(['configurableitems' => $pageId , 'OptionSlug' => $slug])->distinct()->get();
        // if($id > 0){
        //     $Color = Color::where('id',$id)->first();
        // } else {
        //     $Color = '';
        // }
        return view('color/CreateColor',['doorLeafFacing' => $doorLeafFacing]);
    }




    public function storeColor(request $request)
    {
        $update_val = $request->update;
        if(!is_null($update_val)){
            $color = Color::find($update_val);
            $SelectedColor = SelectedColor::where('SelectedColorId',$update_val)->where('SelectedUserId',Auth::user()->id)->first();
        } else {
            $color = new Color;
            $SelectedColor = new SelectedColor;
            $color->created_at = date('Y-m-d H:i:s');
        }

        if($request->DoorLeafFacing == 'Kraft_Paper'){
            $color->DoorLeafFacingValue = 'Painted';
        }

        if(Auth::user()->id == 2){
            $color->Status == 1;
        }
        
        // $color->configurableitems = $request->configurableitems;
        $color->DoorLeafFacing = $request->DoorLeafFacing;
        // $color->DoorLeafFacingValue = $request->DoorLeafFacingValue;
        $color->ColorName = $request->ColorName;
        $color->EnglishName = $request->EnglishName;
        $color->RGB = $request->RGB;
        $color->Hex = $request->Hex;
        $color->ColorCost = $request->ColorCost;
        $color->Status = $request->Status;
        $color->editBy = Auth::user()->id;
        $color->save();

        $selectedId = is_null($update_val) ? $color->id : $update_val;


        $SelectedColor->SelectedCompanyId = Auth::user()->id;
        $SelectedColor->SelectedUserId = Auth::user()->id;
        $SelectedColor->SelectedColorId = $selectedId;
        $SelectedColor->DoorLeafFacing = $request->DoorLeafFacing;
        $SelectedColor->SelectedPrice = $request->ColorCost;
        $SelectedColor->save();

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'The color update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'The color created successfully!');
        }
    }

    public function colorList(){
        // $ConfigurableItems = ConfigurableItems::get();
        $authdata = Auth::user();
        // $Color = Color::leftJoin('options','options.OptionKey','color.DoorLeafFacingValue')->select('color.*', 'options.OptionValue')->get();
        $UserId = Auth::user()->UserType == 2 ? ['1', $authdata->id] : ['1'];
        
        $tt = Color::where(['DoorLeafFacing' => 'Kraft_Paper'])->wherein('editBy',$UserId)->get();
        $countColor = Color::where(['DoorLeafFacing' => 'Kraft_Paper'])->wherein('editBy',$UserId)->count();
        $countSelectedColor = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => 'Kraft_Paper'])->count();
        $checkall = '';
        if($countColor == $countSelectedColor){
            $checkall = 'checked';
        }
        
        $Kraft_Paper = '';
        if(Auth::user()->UserType != 1){
            $Kraft_Paper .= '
            <li>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall" value="Kraft_Paper" '.$checkall.'/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>';
        }
        
        foreach($tt as $row){
           $Kraft_Paper .=  ColorList($row,);
        }

        $tt2 = Color::where([ 'DoorLeafFacing' => 'Laminate'])->wherein('editBy',$UserId)->get();
        $countColor2 = Color::where(['DoorLeafFacing' => 'Laminate'])->wherein('editBy',$UserId)->count();
        $countSelectedColor2 = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => 'Laminate'])->count();
        $checkall2 = '';
        if($countColor2 == $countSelectedColor2){
            $checkall2 = 'checked';
        }
        
        $Laminate = '';
        if(Auth::user()->UserType != 1){
            $Laminate .= '
            <li>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall" value="Laminate" '.$checkall2.'/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>';
        }
        
        foreach($tt2 as $row){
            $Laminate .=  ColorList($row);
        }

        $tt3 = Color::where(['DoorLeafFacing' => 'PVC'])->wherein('editBy',$UserId)->get();
        $countColor3 = Color::where(['DoorLeafFacing' => 'PVC'])->wherein('editBy',$UserId)->count();
        $countSelectedColor3 = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => 'PVC'])->count();
        $checkall3 = '';
        if($countColor3 == $countSelectedColor3){
            $checkall3 = 'checked';
        }
        
        $PVC = '';
        if(Auth::user()->UserType != 1){
            $PVC .= '
            <li>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall" value="PVC" '.$checkall3.'/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>';
        }
        
        foreach($tt3 as $row){
            $PVC .=  ColorList($row);
        }

        $tt4 = Color::where(['DoorLeafFacing' => 'Veneer'])->wherein('editBy',$UserId)->get();
        $countColor4 = Color::where(['DoorLeafFacing' => 'Veneer'])->wherein('editBy',$UserId)->count();
        $countSelectedColor4 = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => 'Veneer'])->count();
        $checkall4 = '';
        if($countColor4 == $countSelectedColor4){
            $checkall4 = 'checked';
        }
        
        $Veneer = '';
        if(Auth::user()->UserType != 1){
            $Veneer .= '
            <li>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall" value="Veneer" '.$checkall4.'/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>';
        }
        
        foreach($tt4 as $row){
            $Veneer .=  ColorList($row);
        }

        $tt4 = Color::wherein('editBy',$UserId)->get();
        $countColor4 = Color::where(['DoorLeafFacing' => 'Veneer'])->wherein('editBy',$UserId)->count();
        $countSelectedColor4 = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => 'Veneer'])->count();
        $checkall4 = '';
        if($countColor4 == $countSelectedColor4){
            $checkall4 = 'checked';
        }
        
        $other = '';
        if(Auth::user()->UserType != 1){
            $other .= '
            <li>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall" value="Veneer" '.$checkall4.'/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>';
        }
        
        foreach($tt4 as $row){
            $DoorLeafFacing = $row->DoorLeafFacing;
            if($DoorLeafFacing !=  'Kraft_Paper' && $DoorLeafFacing !=  'Laminate' && $DoorLeafFacing !=  'PVC'  && $DoorLeafFacing !=  'Veneer'){
                $other .=  ColorList($row);
            }
        }


        return view('color/ColorList',['Kraft_Paper' => $Kraft_Paper, 'Laminate' => $Laminate, 'PVC' => $PVC, 'Veneer' => $Veneer, 'other' => $other]);
    }

    public function colorListCompany(){
        // $ConfigurableItems = ConfigurableItems::get();
        $authdata = Auth::user();
        // $Color = Color::leftJoin('options','options.OptionKey','color.DoorLeafFacingValue')->select('color.*', 'options.OptionValue')->get();
        if($authdata->UserType){
            $UserId = ['1', $authdata->id];
            $tt = Color::where(['DoorLeafFacing' => 'Kraft_Paper'])->wherein('editBy',$UserId)->get();
            $Kraft_Paper = '';
            foreach($tt as $row){
               $Kraft_Paper .=  ColorListCompany($row,);
            }

            $tt2 = Color::where([ 'DoorLeafFacing' => 'Laminate'])->wherein('editBy',$UserId)->get();
            $Laminate = '';
            foreach($tt2 as $row){
                $Laminate .=  ColorListCompany($row);
            }

            $tt3 = Color::where(['DoorLeafFacing' => 'PVC'])->wherein('editBy',$UserId)->get();
            $PVC = '';
            foreach($tt3 as $row){
                $PVC .=  ColorListCompany($row);
            }

            $tt4 = Color::where(['DoorLeafFacing' => 'Veneer'])->wherein('editBy',$UserId)->get();
            $Veneer = '';
            foreach($tt4 as $row){
                $Veneer .=  ColorListCompany($row);
            }

            $tt4 = Color::wherein('editBy',$UserId)->get();
            $other = '';
            foreach($tt4 as $row){
                $DoorLeafFacing = $row->DoorLeafFacing;
                if($DoorLeafFacing !=  'Kraft_Paper' && $DoorLeafFacing !=  'Laminate' && $DoorLeafFacing !=  'PVC'  && $DoorLeafFacing !=  'Veneer'){
                    $other .=  ColorListCompany($row);
                }
            }


            return view('color/ColorList',['Kraft_Paper' => $Kraft_Paper, 'Laminate' => $Laminate, 'PVC' => $PVC, 'Veneer' => $Veneer, 'other' => $other]);
        }

        return null;
    }

    public function updateSelectedColorOption(request $request): void{
        $ids = $request->selectedId;
        $className = $request->className;
        $UserId = Auth::user()->id;
        if(!empty($ids) && count($ids)){
            if($className == "Painted"){
                $optionKey ="Painted";
                SelectedColor::where(['SelectedUserId'=>$UserId ,'DoorLeafFacing'=>$optionKey])->delete();
                foreach($ids as $id){
                    $electedOption= [];
                    $electedOption = Color::where('id',$id)->first();
                    if(!empty( $electedOption ) && (array) $electedOption !== []){
                        $selectedOption = new SelectedColor();
                        // $selectedOption->configurableitems = $electedOption->configurableitems;
                        $selectedOption->SelectedColorId = $electedOption->id;
                        $selectedOption->SelectedUserId = Auth::user()->id;
                        $selectedOption->DoorLeafFacing = $electedOption->DoorLeafFacing;
                        $selectedOption->save();
                    }
                }
            }
            
            if($className == "Kraft_Paper"){
                $optionKey ="Kraft_Paper";
                SelectedColor::where(['SelectedUserId'=>$UserId ,'DoorLeafFacing'=>$optionKey])->delete();
                foreach($ids as $id){
                    $electedOption= [];
                    $electedOption = Color::where('id',$id)->first();
                    if(!empty( $electedOption ) && (array) $electedOption !== []){
                        $selectedOption = new SelectedColor();
                        // $selectedOption->configurableitems = $electedOption->configurableitems;
                        $selectedOption->SelectedColorId = $electedOption->id;
                        $selectedOption->SelectedUserId = Auth::user()->id;
                        $selectedOption->DoorLeafFacing = $electedOption->DoorLeafFacing;
                        $selectedOption->save();
                    }
                }
            }
            
            if($className == "Laminate"){
                $optionKey ="Laminate";
                SelectedColor::where(['SelectedUserId'=>$UserId ,'DoorLeafFacing'=>$optionKey])->delete();
                foreach($ids as $id){
                    $electedOption= [];
                    $electedOption = Color::where('id',$id)->first();
                    if(!empty( $electedOption ) && (array) $electedOption !== []){
                        $selectedOption = new SelectedColor();
                        // $selectedOption->configurableitems = $electedOption->configurableitems;
                        $selectedOption->SelectedColorId = $electedOption->id;
                        $selectedOption->SelectedUserId = Auth::user()->id;
                        $selectedOption->DoorLeafFacing = $electedOption->DoorLeafFacing;
                        $selectedOption->save();
                    }
                }
            }
            
            if($className == "PVC"){
                $optionKey ="PVC";
                SelectedColor::where(['SelectedUserId'=>$UserId,'DoorLeafFacing'=>$optionKey])->delete();
                foreach($ids as $id){
                    $electedOption= [];
                    $electedOption = Color::where('id',$id)->first();
                    if(!empty( $electedOption ) && (array) $electedOption !== []){
                        $selectedOption = new SelectedColor();
                        // $selectedOption->configurableitems = $electedOption->configurableitems;
                        $selectedOption->SelectedColorId = $electedOption->id;
                        $selectedOption->SelectedUserId = Auth::user()->id;
                        $selectedOption->DoorLeafFacing = $electedOption->DoorLeafFacing;
                        $selectedOption->save();
                    }
                }
            }
            
            if($className == "Veneer"){
                $optionKey ="Veneer";
                SelectedColor::where(['SelectedUserId'=>$UserId,'DoorLeafFacing'=>$optionKey])->delete();
                foreach($ids as $id){
                    $electedOption= [];
                    $electedOption = Color::where('id',$id)->first();
                    if(!empty( $electedOption ) && (array) $electedOption !== []){
                        $selectedOption = new SelectedColor();
                        // $selectedOption->configurableitems = $electedOption->configurableitems;
                        $selectedOption->SelectedColorId = $electedOption->id;
                        $selectedOption->SelectedUserId = Auth::user()->id;
                        $selectedOption->DoorLeafFacing = $electedOption->DoorLeafFacing;
                        $selectedOption->save();
                    }
                }
            }
            
            echo json_encode(["status"=>"ok","msg"=>"options are updated"]);
        } else {
            echo json_encode(["status"=>"errror","msg"=>"please check options"]);
        }
    }

    public function selectColorOption(){
        $authdata = Auth::user();
        $electedColorOption = Color::all();
        if(!empty($electedColorOption) && (array)$electedColorOption !== [] ){
            foreach($electedColorOption as $row){
                $selectedOption = SelectedColor::where([['SelectedColorId',$row->id],['SelectedUserId',$authdata->id]])->first();

                $row->selected = !empty($selectedOption) && (array)$selectedOption !== [] ? 1 : 0;


            }

        }
        
        // print_r( $electedColorOption);
        // die();

         return view('option/SelectedColorOption',['electedColorOption' => $electedColorOption]);


    }

    public function ColorDoorLeafFacing(Request $request): string
    {
        $UnderAttribute = $request->UnderAttribute;
        // $tt = Option::distinct()->where(['UnderAttribute' => $UnderAttribute])->get(['OptionKey','OptionValue']);

        $tt = GetOptions(['door_leaf_facing.doorLeafFacing' => $UnderAttribute ,'door_leaf_facing.Status' => 1], "join","door_leaf_facing");

        $innerHtml = '<option value="">select Door Leaf Facing Value</option>';
        if(!empty($tt)){
            foreach($tt as $rr){
                // if($rr->OptionKey != 'Primed'){
                    $innerHtml .= '<option value="'.$rr->Key.'">'.$rr->doorLeafFacingValue.'</option>';
                // }
                // if($rr->OptionKey == 'Paint_Finish'){
                //     $innerHtml = '<option value="'.$rr->Key.'">'.$rr->doorLeafFacingValue.'</option>';
                // }
            }
        }
        
        return $innerHtml;
    }


    public function editcolor(Request $request)
    {
        $id = $request->upd;
        $pageId = $request->pageId;
        $upd = Color::find($id);
        return redirect()->route('create-color',$pageId)->with('upd',$upd);
    }

    public function deletecolor(Request $request): string
    {
        Color::where('id', $request->id)->delete();
        $flash = "deleted";
        return 'success';
    }
}
