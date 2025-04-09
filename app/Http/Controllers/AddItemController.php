<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\RuleMaster;
use App\Models\ItemField;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\Option;
use URL;

class AddItemController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $data,$ItemId=null)
    {

    	if(isset($ItemId)){
            $Item=[];
            $ItemId      =Crypt::decrypt($ItemId);
            $Item        =Item::findorfail($ItemId);
            return view('additem',['item'=>$Item]);
        }else{
            return view('additem');
        }
        
        //print_r($Item);
    }

    public function SaveItem(Request $request)
    {   //return($_POST['data']);exit;

        $dataarray = json_decode((string) $_POST['data']);
        $dataitem=[];
        $dataitem['StatusCode']=0;
        $dataitem['ItemId'] = null;
        foreach($dataarray as $key=>$data){
            if($key=='ItemId' && $data!=''){
                $dataitem['ItemId'] =Crypt::decrypt($data);
            }
            
            if (count($dataarray->Fields)==0) {
                if ($key=='ItemName' && isset($data)) {
                    $dataitem['ItemName']    =$data;
                    $dataitem['ItemType']    =2;
                    $dataitem['ItemStatus']  =1;
                    $dataitem['StatusCode']  =1;
                    /* validating form data by calling validator method */
                    $dataitem['ItemValidate']=$this->itemvalidator($dataitem);
                    $dataitem['ItemId']=$this->UpdateOrCreateItem($dataitem)->id;
                } elseif ($key=='ItemLogo'&& isset($data)) {
                    $dataitem['ItemPhoto']=$key;
                }

                $dataitem['StatusCode']=http_response_code();
            } elseif ($key=='ItemName' && isset($data)) {
                $dataitem['ItemName']    =$data;
                $dataitem['ItemType']    =1;
                $dataitem['ItemStatus']  =1;
                $dataitem['StatusCode']  =1;
                /* validating form data by calling validator method */
                $dataitem['ItemValidate']=$this->itemvalidator($dataitem);
                $dataitem['ItemId']=$this->UpdateOrCreateItem($dataitem)->id;
            } elseif ($key=='ItemLogo'&& isset($data)) {
                $dataitem['ItemPhoto']=$key;
            } elseif ($key=='Fields' && !empty($data)) {
                $CategoryId=null;
                foreach ($data as $keyfields => $datafields) {
                    $dataitem['Category'][$keyfields]['ItemId']         =$dataitem['ItemId'];
                    $dataitem['Category'][$keyfields]['CategoryName']   =$datafields->CategoryName;

                    $CategoryId = $this->UpdateOrCreateCategory($dataitem['Category'][$keyfields])->id;
                    $dataitem['Category'][$keyfields]['CategoryId']     =$CategoryId;
                    //return response()->json($dataitem);exit;
                    $dataitem['Category'][$keyfields]['FieldId']              =$datafields->FieldId ?? NULL;
                    $dataitem['Category'][$keyfields]['FieldName']            =$datafields->FieldName ?? NULL;
                    $dataitem['Category'][$keyfields]['CategoryId']           =$CategoryId;
                    $dataitem['Category'][$keyfields]['FieldOrder']           =$keyfields+1;
                    $dataitem['Category'][$keyfields]['FieldType']            =$datafields->FieldType ?? NUll;
                    if ($datafields->FieldType=='text') {
                        $dataitem['Category'][$keyfields]['DefaultValueText']   =$datafields->DefaultValue ?? Null;
                        $dataitem['Category'][$keyfields]['DefaultValueNumber'] =Null;
                    } elseif ($datafields->FieldType=='number') {
                        $dataitem['Category'][$keyfields]['DefaultValueText']   =Null;
                        $dataitem['Category'][$keyfields]['DefaultValueNumber'] =$datafields->DefaultValue ?? Null;
                    } else{
                    $dataitem['Category'][$keyfields]['DefaultValueText']   =Null;
                    $dataitem['Category'][$keyfields]['DefaultValueNumber'] =Null;
                    }
                    
                    $dataitem['Category'][$keyfields]['Instruction']          =$datafields->Instruction ?? Null;
                    $dataitem['Category'][$keyfields]['Price']                =$datafields->Price ?? Null;
                    $dataitem['Category'][$keyfields]['MinValue']             =$datafields->MinValue ?? Null;
                    $dataitem['Category'][$keyfields]['MaxValue']             =$datafields->MaxValue ?? Null;
                    $dataitem['Category'][$keyfields]['FiledValidation']      =$datafields->FiledValidation ?? Null;
                    $dataitem['Category'][$keyfields]['Required']             =$datafields->Required ?? Null;
                    $dataitem['Category'][$keyfields]['ReadOnly']             =$datafields->ReadOnly ?? Null;
                    $dataitem['Category'][$keyfields]['HideField']            =$datafields->HideField ?? Null;
                    $dataitem['Category'][$keyfields]['Minheight']            =$datafields->Minheight ?? Null;
                    $dataitem['Category'][$keyfields]['Maxheight']            =$datafields->Maxheight ?? Null;
                    $dataitem['Category'][$keyfields]['Minwidth']             =$datafields->Minwidth ?? Null;
                    $dataitem['Category'][$keyfields]['Maxwidth']             =$datafields->Maxwidth ?? Null;
                    $dataitem['Category'][$keyfields]['Heading']              =$datafields->Heading ?? Null;
                    $dataitem['Category'][$keyfields]['FontSize']             =$datafields->FontSize ?? Null;
                    $dataitem['Category'][$keyfields]['Option']              =isset($datafields->OptionArray)?json_encode($datafields->OptionArray):Null;

                    $dataitem['Category']['FieldId']  =$this->UpdateOrCreateField($dataitem['Category'][$keyfields])->id;

                }
            }
            
            $dataitem['StatusCode']=http_response_code();

        }


        $dataitem['ItemId']=Crypt::encrypt($dataitem['ItemId']);
        return response()->json(['status'=>'success', 'dataitem'=>$dataitem]);
    }


    protected function itemvalidator(array $data)
    { //return $data;
        return Validator::make($data, [
            'ItemName'   => 'required|string|max:255'
        ])->validate();
    }

     /**
     * Create a new Item instance.
     *
     * @param  array  $data
     * @return \App\Models\Item
     */
    protected function UpdateOrCreateItem(array $data)
    {
        $matchThese = ['id'=>$data['ItemId']];
        return Item::updateOrCreate($matchThese,[
            'ItemName'          => $data['ItemName'],
            'ItemType'          => $data['ItemType'],
            'ItemStatus'        => $data['ItemStatus'],
        ]);
    }
    
    protected function UpdateOrCreateCategory(array $data)
    {  //return $data;
        $matchThese = ['ItemId'=>$data['ItemId'],'CategoryName'=>$data['CategoryName']];
        return ItemCategory::updateOrCreate($matchThese,[
            'CategoryName'            => $data['CategoryName'],
            'ItemId'                  => $data['ItemId'],]);
    }
    
    protected function UpdateOrCreateField(array $data)
    {  //return $data;
        $matchThese = ['id'=>$data['FieldId']];
        return ItemField::updateOrCreate($matchThese,[
            'FieldName'            => $data['FieldName'],
            'CategoryId'           => $data['CategoryId'],
            'FieldOrder'           => $data['FieldOrder'],
            'FieldType'            => $data['FieldType'],
            'DefaultValueText'     => $data['DefaultValueText'],
            'DefaultValueNumber'   => $data['DefaultValueNumber'],
            'Instruction'          => $data['Instruction'],
            'Price'                => $data['Price'],
            'MinValue'             => $data['MinValue'],
            'MaxValue'             => $data['MaxValue'],
            'FiledValidation'      => $data['FiledValidation'],
            'Required'             => $data['Required'],
            'ReadOnly'             => $data['ReadOnly'],
            'HideField'            => $data['HideField'],
            'Minheight'            => $data['Minheight'],
            'Maxheight'            => $data['Maxheight'],
            'Minwidth'             => $data['Minwidth'],
            'Maxwidth'             => $data['Maxwidth'],
            'Heading'              => $data['Heading'],
            'FontSize'             => $data['FontSize'],
            'Options'              => $data['Option'],
        ]);
    }

    public function ChangeCategoryStatus(Request $request){
        //return($request);exit;
        if($request['id']){
            $ItemCategory               = ItemCategory::find(crypt::decrypt($request->id));
            if($ItemCategory){
                $ItemCategory->CategoryStatus   = ($ItemCategory->CategoryStatus==1) ? 2 : 1;
                $result=$ItemCategory->save();
                $statuscode=http_response_code();
            }else{
                $statuscode='fail';
            }
        }
        
        return response()->json(['success'=>$statuscode,'itemcategory'=>$ItemCategory]);
    }

    public function ChangeFieldStatus(Request $request){
        //return($request);exit;
        if($request['id']){
            $ItemField               = ItemField::find($request->id);
            if($ItemField){
                $ItemField->FieldStatus   = ($ItemField->FieldStatus==1) ? 2 : 1;
                $result=$ItemField->save();
                $statuscode=http_response_code();
            }else{
                $statuscode='fail';
            }
        }
        
        return response()->json(['success'=>$statuscode,'itemfield'=>$ItemField]);
    }
    
    public function addRuleList(Request $request, $ItemId=false){
             $ItemId      =  Crypt::decrypt($ItemId);
             $item        = Item::findorfail($ItemId);
             $categoryIds = [];
             $itemField   = [];
             $categoryIds = ItemCategory::where('ItemId',$ItemId)->pluck('id');
             if(!empty($categoryIds)):
                $itemField   = ItemField::whereIn('CategoryId',$categoryIds)->get();
             endif;
             
            return view('addRule.add',['item' => $item, 'categoryIds' => $categoryIds, 'itemField' => $itemField]);
    }
    
    public function addNewRulesPost(Request $request){
           $request = $request->all();
           $insert = new RuleMaster;
           $insert->itemIds =  $request['itemIds'];
           $insert->ifCategoryName =  json_encode($request['categoryListName']);
           $insert->ifconditionsOption =  json_encode($request['conditionsOption']);
           $insert->ifmaxMinValue =  json_encode($request['ifmaxMinValue']);
           $insert->ifConditions =  (empty($request['ifConditions']) ? '' : json_encode($request['ifConditions']));
           $insert->thenShowMaxMin =   json_encode($request['thenShowMaxMin']);
           $insert->thencategoryListName =  json_encode($request['thencategoryListName']);
           $insert->thenmaxMinValue =  json_encode($request['thenmaxMinValue']);
           $insert->thenConditions =   (empty($request['thenConditions']) ? '' : json_encode($request['thenConditions']));
           $insert->created_at = time();
           $insert->updated_at = time();
           $insert->save();
            return redirect()->route('add.rule.list',Crypt::encrypt($request['itemIds']))->with('success' , 'Rule has been added successfully');
    }
}
