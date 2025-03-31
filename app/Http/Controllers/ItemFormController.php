<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\SideScreenItemMaster;
use App\Models\SideScreenItem;
use App\Models\Option;
use App\Models\Company;
use App\Models\User;
use App\Models\NonConfigurableItems;
use App\Models\ArchtectureItemForms;
use App\Models\BOMCalculation;
use Illuminate\Support\Facades\Redirect;
use App\Models\ItemMaster;
use App\Models\ScreenBOMCalculation;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use URL;
use DB;
class ItemFormController extends Controller
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
     * Show the application additem or create item.
     *
     *
     */
    public function index()
    {

        $item=Item::all();
        $options_data = Option::where('is_deleted',0)->get();
        // $company_data = User::where('userType',2)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('companies.*')->where('users.UserType',2)->get();

        // return view('Items/MBK/AddItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
        return view('Architecture.CreateDynamicItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);




    }

    public function storeFileName(Request $request){

            $companyDetails = Company::where('UserId',$request->UserId)->first();

            if(!empty($request->CompanyFileName)){
                $CompanyName = str_replace(" ","",$request->CompanyFileName);
            }else{
                $CompanyName = str_replace(" ","",$companyDetails['CompanyName']);
            }

            $rand = rand();
            // // die();
            $fieldsValue =json_encode($request->form_value);


             $architectureItemForm = new ArchtectureItemForms();

             $architectureItemForm->UserId = $request->UserId;
             $architectureItemForm->FormName = $CompanyName.$rand;
             $architectureItemForm->FileName = $CompanyName.$rand;
             $architectureItemForm->FieldValue = $fieldsValue;
             $architectureItemForm->Status = 1;

             $inseted = $architectureItemForm->save();

            if($inseted){

              echo json_encode(array("status"=>'success',"msg"=>'door assigned successfully'));

            }else{
                echo json_encode(array("status"=>'failed',"msg"=>'there is some technical problem please try again later'));
            }




    }

    public function configurationFormList(){

        $forms = DB::table('archtecture_item_forms')
        ->join('companies', 'companies.id', '=', 'archtecture_item_forms.CompanyID')
        ->select('archtecture_item_forms.*', 'companies.CompanyName','companies.CompanyPhoto')
        ->orderBy('archtecture_item_forms.id', 'desc')
        ->get();
        // echo"<pre>";
        // print_r( $forms);
        // die();

        return view('Architecture.DynamicFormList',['formlist'=>$forms]);
    }

    public function viewPage($id){


        $form=ArchtectureItemForms::find($id);


        // echo"<pre>";
        // print_r( $form->FileName);
        if(file_exists('../resources/views/Forms/'.$form->FileName)){

            $item=Item::all();
        $options_data = Option::where('is_deleted',0)->get();
        $company_data = Company::get();
        // return view('Items/AddItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
        // return view('Forms.newForm',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
        return view('Forms.'.$form->FormName,['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);

        }else{
            return Redirect::back()->withErrors([' File '.$form->FormName.' not exist.']);
        }



    }

    public function addNonConfigurableItems(){
        $nonconfig = NonConfigurableItems::orderBy('id','desc')->get();
        $i = 1;
        $tbl =  '';
        foreach($nonconfig as $nonconfigs){
            $tbl .=
            '
                <tr>
                    <td>'.$i.'</td>
                    <td><img src="'.url('/').'/uploads/non-configurable-items/'.$nonconfigs->image.'" alt="Image" style="width:50px"></td>
                    <td>'.$nonconfigs->name.'</td>
                    <td>'.$nonconfigs->price.'</td>
                    <td>
                    <script type="text/javascript">
                        document.write(ReadMore(30,"'.$nonconfigs->description.'"))
                    </script>
                    </td>
                    <td><button type="submit" name="id" value="'.$nonconfigs->id.'" class="btn btn-info">Edit</button></td>
                </tr>
            ';
            $i++;
        }
        return view('Items.NonConfigurableItems',compact('tbl'));
    }

    public function saveNonConfigurableItems(Request $request){

        if ($request->hasFile('image')) {
            $valid = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/non-configurable-items');
            $image->move($destinationPath, $name);
        }

        $update_val = $request->updid;
        if(!is_null($update_val)){
            $NonConfigurableItems = NonConfigurableItems::find($update_val);
        } else {
            $NonConfigurableItems = new NonConfigurableItems();
        }
        $NonConfigurableItems->name = $request->item_name;
        $NonConfigurableItems->price = $request->price;
        $NonConfigurableItems->description = $request->description;
        if(isset($name)){
            $NonConfigurableItems->image = $name;
        }
        $NonConfigurableItems->save();
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Update Non Configurable Item successfully!');
        } else {
            return redirect()->back()->with('success', 'Added Non Configurable Item successfully!');
        }
    }

    public function updNonconfigurable(Request $request)
    {
        $id = $request->id;
        $aa = NonConfigurableItems::find($id);
        return redirect()->back()->with('data' , $aa );
    }


    public function item_remove(request $request){

        $items = ItemMaster::select('items.*')
        ->join('items', 'items.itemId', '=', 'item_master.itemID')
        ->where('item_master.id',$request->id)
        ->get()->first();

        ItemMaster::where('id',$request->id)->delete();

        $quotation = Quotation::where('id',$items->QuotationId)->first();
        if(!empty($items->itemId)){
            $ItemMaster = ItemMaster::where('itemID',$items->itemId)->get()->count();
            if($ItemMaster == 0){
                BOMCalculation::where(['itemId'=>$items->itemId,'QuotationId'=>$items->QuotationId])->delete();
            }else{
                $version_id = QuotationVersion::where('quotation_id', $items->QuotationId)->where('id', $items->VersionId)->value('version');
                if(!empty($version_id)){
                    BOMCalculation::where('QuotationId',$items->QuotationId)->where('VersionId',$items->VersionId)->where('itemId',$items->itemId)->delete();
                }

                BOMUpdate($items, $quotation->configurableitems);
                // BOMQuatityOfDoorUpdate($items->itemId,$items->QuotationId);
            }
        }

        return 'success';
    }

    public function screen_remove(request $request){

        $items = SideScreenItemMaster::select('side_screen_items.*')
        ->join('side_screen_items', 'side_screen_items.id', '=', 'side_screen_item_master.ScreenId')
        ->where('side_screen_item_master.id',$request->id)
        ->get()->first();

        SideScreenItemMaster::where('id',$request->id)->delete();

        $quotation = Quotation::where('id',$items->QuotationId)->first();
        if(!empty($items->id)){
            $SideScreenItemMaster = SideScreenItemMaster::where('ScreenID',$items->id)->get()->count();
            if($SideScreenItemMaster == 0){
                ScreenBOMCalculation::where(['ScreenId'=>$items->id,'QuotationId'=>$items->QuotationId])->delete();
            }else{
                $version_id = QuotationVersion::where('quotation_id', $items->QuotationId)->where('id', $items->VersionId)->value('version');
                if(!empty($version_id)){
                    ScreenBOMCalculation::where('QuotationId',$items->QuotationId)->where('VersionId',$items->VersionId)->where('ScreenId',$items->id)->delete();
                }

                sideScreenBOM($items);
            }
        }

        return 'success';
    }
}
