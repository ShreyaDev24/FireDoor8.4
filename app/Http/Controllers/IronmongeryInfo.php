<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IronmongeryInfoExport;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Company;
use App\Models\User;
use App\Models\IronmongeryInfoModel;
use App\Models\DoorSchedule;
use App\Models\SelectedIronmongery;
use App\Models\ConfigurableItems;
use App\Models\Option;
use App\Models\AddIronmongery;
use App\Models\IronmongeryName;
use App\Models\SettingCurrency;
use App\Imports\DoorScheduleImport;
use DB;
use URL;
use Hash;
use Session;

class IronmongeryInfo extends Controller
{


    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $loginUser = auth()->user();
            if(!in_array($loginUser->UserType,["1","2"])){

                   return redirect("/");
            }
            
            return $next($request);

        });

        //        $categoryArray = [
        //            "Hinges" => ["Width","Height","Thickness","Knuckle Diameter"],
        //            "Floor Spring" => ["Length","Width","Height"],
        //            "Locks and Latches" => ["Case Depth","Backset"],
        //            "Flush Bolts" => ["Length","Width","Mortice Depth"],
        //            "Overhead Closers" => ["Width","Height","Depth","Min Door Width","Min Door Height","Min Door Thickness"],
        //            "Pull Handles" => ["Length","Fixing hole centres","Projection"],
        //            "Push Handles" => ["Length","Width","Thickness"],
        //            "Kick Plates" => ["Length","Width","Thickness"],
        //            "Door Selectors" => ["Width","Height"],
        //            "Panic Hardware" => ["Max Door Height","Max Door Width"],
        //        ];

        //        echo "<pre>";
        //        print_r(now());
        //        foreach($categoryArray as $x => $val) {
        //            echo "$x<br>";
        //        }
        //        die;


    }

    public function index($id="")
    {  //dd($id);
        // $ConfigurableItems = ConfigurableItems::get();

        if (Auth::user()->UserType == 2) {
            $myAdminGroup = getMyCreatedAdmins();
            $userId = $myAdminGroup;
            // $useTbl = array_merge(['1'], $myAdminGroup);
            }else{

                $user = auth()->user();

                $userId = [$user->id];
            }

        $option = Option::distinct()->where(['OptionSlug'=>'fire_rating','configurableitems'=>1])->get(['OptionKey','OptionValue']);
        if(!empty($id)){


            if(Auth::user()->id==1){
                $IronmongeryInfo = IronmongeryInfoModel::where('GeneratedKey',$id)->first();
            }
            else{
                $IronmongeryInfo = IronmongeryInfoModel::whereIn('UserId', $userId)->where(['GeneratedKey' => $id])->first();
            }

            // $pageId = $IronmongeryInfo->configurableitems;
            // $option = Option::where(['configurableitems'=>$pageId,'OptionSlug'=>'fire_rating'])->get();
            if(isset($IronmongeryInfo->CategoryFieldsJSON)){
                $categoryFieldsArray = json_decode($IronmongeryInfo->CategoryFieldsJSON);
            }
            
            if(!empty($IronmongeryInfo) && (array)$IronmongeryInfo !== []){
                return view('IronmongeryInfo.CreateIronmongeryInfo',['IronmongeryInfo' => $IronmongeryInfo, 'option' => $option]);
            } else {
                return redirect()->route('ironmongery-info/reports');
            }
        } else {

            return view('IronmongeryInfo.CreateIronmongeryInfo',['option' => $option]);
        }

    }




    public function store(request $request)
    {
        $useId = CompanyUsers();
        if(!empty($request->FireRating[0]) && !empty($request->Category) && !empty($request->Name) && !empty($request->Code) && !empty($request->Description) && !empty($request->Price) && !empty($request->Supplier)){

            if(property_exists($request, 'update') && $request->update !== null){
                if(Auth::user()->id==1){
                    $IronmongeryInfo = IronmongeryInfoModel::where([ 'GeneratedKey' => $request->update ])->first();
                }
                else{
                    $IronmongeryInfo = IronmongeryInfoModel::where('UserId', Auth::user()->id)->where(['GeneratedKey' => $request->update ])->first();
                }

                $count = count($request->FireRating);
                $i = 0;
                // while($count > $i){
                    $firerating = $request->FireRating[$i];

                    $Finishescount = count($request->Finishes);
                    $k = $Finishescount - 1;
                    $j = 0;
                    $Finishes = '';
                    while($Finishescount > $j){
                        if($k == $j){
                            $Finishes .= $request->Finishes[$j];
                        } else {
                            $Finishes .= $request->Finishes[$j].",";
                        }
                        
                    $j++;
                    }

                    $Fireratingcount = count($request->FireRating);
                    $l = $Fireratingcount - 1;
                    $m = 0;
                    // $FireRating = '';
                    // while($Finishescount > $l){
                    //     if($l == $m){
                    //         $FireRating .= $request->FireRating[$l];
                    //     } else {
                    //         $FireRating .= $request->FireRating[$l].",";
                    //     }

                    // $l++;
                    // }

                    if(!empty($IronmongeryInfo)){
                        $categoryArray = [
                            "Hinges" => ["Width","Height","Thickness","Knuckle Diameter"],
                            "Floor Spring" => ["Length","Width","Height"],
                            "Locks and Latches" => ["Case Depth","Backset"],
                            "Flush Bolts" => ["Length","Width","Mortice Depth"],
                            "Overhead Closers" => ["Width","Height","Depth","Min Door Width","Min Door Height","Min Door Thickness"],
                            "Pull Handles" => ["Length","Fixing hole centres","Projection"],
                            "Push Handles" => ["Length","Width","Thickness"],
                            "Kick Plates" => ["Length","Width","Thickness"],
                            "Door Selectors" => ["Width","Height"],
                            "Panic Hardware" => ["Max Door Height","Max Door Width"],
                        ];
                        $CategoryFieldsJSON = [];
                        foreach($categoryArray as $index => $val){
                            foreach($val as $indexInner => $valInner){
                                $dynamicFieldKey =  preg_replace('/\s+/', '', $index.$valInner);
                                $CategoryFieldsJSON[$dynamicFieldKey] = $request->$dynamicFieldKey;
                            }
                        }

                        if($request->hasFile('Image')){
                            $file = $request->file('Image');
                            $ImageName = rando().$file->getClientOriginalName();
                            $filepath = public_path('uploads/IronmongeryInfo/');
                            $ImageExtension = $file->getClientOriginalExtension();
                            if(!in_array($ImageExtension,["jpg", "jpeg", "png", "jpg", "JPG", "JPEG", "PNG"])){
                                return redirect('IronmongeryInfo/update/'.$request->update);die;
                            }
                            
                            $file->move($filepath,$ImageName);
                            $IronmongeryInfo->Image = $ImageName;
                        }

                        if($request->hasFile('PdfSpecification')){
                            $file = $request->file('PdfSpecification');
                            $PdfSpecificationName = rando().$file->getClientOriginalName();
                            $filepath = public_path('uploads/IronmongeryInfo/');
                            $PdfSpecificationExtension = $file->getClientOriginalExtension();
                            if(!in_array($PdfSpecificationExtension,["pdf", "PDF"])){
                                File::delete($filepath.$IronmongeryInfo->Image);
                                return redirect('ironmongery-info/update/'.$request->update);die;
                            }
                            
                            $file->move($filepath,$PdfSpecificationName);
                            File::delete($filepath.$IronmongeryInfo->PdfSpecification);
                            File::delete($filepath.$IronmongeryInfo->Image);
                            $IronmongeryInfo->PdfSpecification = $PdfSpecificationName;
                        }




                        // $IronmongeryInfo->configurableitems = $request->configurableitems;
                        $IronmongeryInfo->Category = $request->Category;
                        $IronmongeryInfo->CategoryFieldsJSON = json_encode($CategoryFieldsJSON);
                        $IronmongeryInfo->Name = $request->Name;
                        $IronmongeryInfo->Code = $request->Code;
                        // $IronmongeryInfo->Dimensions = $request->Dimensions;
                        $IronmongeryInfo->Description = $request->Description;
                        $IronmongeryInfo->Finishes = $Finishes;
                        $IronmongeryInfo->FireRating = implode(",",$request->FireRating);
                        // $IronmongeryInfo->FireRating = $firerating;
                        $IronmongeryInfo->FireCartNoUK = $request->FireCartNoUK;
                        $IronmongeryInfo->FireCartNoEU = $request->FireCartNoEU;
                        $IronmongeryInfo->Price = $request->Price;

                        // tkt-485 task
                        $IronmongeryInfo->staticWidth = $request->staticWidth;
                        $IronmongeryInfo->staticHeight = $request->staticHeight;
                        $IronmongeryInfo->staticDepth = $request->staticDepth;
                        $IronmongeryInfo->distanceFromBottomOfDoor = $request->distanceFromBottomOfDoor;
                        $IronmongeryInfo->distanceFromLeadingEdgeOfDoor = $request->distanceFromLeadingEdgeOfDoor;
                        $IronmongeryInfo->centered = $request->centered;
                        //end

                        $IronmongeryInfo->intumescentseal_fd30 = $request->intumescentseal_fd30;
                        $IronmongeryInfo->intumescentseal_fd30_price = $request->intumescentseal_fd30_price;

                        $IronmongeryInfo->intumescentseal_fd60 = $request->intumescentseal_fd60;
                        $IronmongeryInfo->intumescentseal_fd60_price = $request->intumescentseal_fd60_price;

                        $IronmongeryInfo->ManMinutes = $request->ManMinutes;
                        $IronmongeryInfo->MachineMinutes = $request->MachineMinutes;

                        $IronmongeryInfo->Supplier = $request->Supplier;
                        $IronmongeryInfo->Status = $request->Status;
                        $IronmongeryInfo->save();
                    }

                    $i++;
                // }



                $IronmongeryInfoId = $IronmongeryInfo->id;
                if(!empty($IronmongeryInfoId)){
                    return redirect()->back()->with('success', 'The Ironmongery Info update successfully!');
                }
            } else {

                $categoryArray = [
                    "Hinges" => ["Width","Height","Thickness","Knuckle Diameter"],
                    "Floor Spring" => ["Length","Width","Height"],
                    "Locks and Latches" => ["Case Depth","Backset"],
                    "Flush Bolts" => ["Length","Width","Mortice Depth"],
                    "Overhead Closers" => ["Width","Height","Depth","Min Door Width","Min Door Height","Min Door Thickness"],
                    "Pull Handles" => ["Length","Fixing hole centres","Projection"],
                    "Push Handles" => ["Length","Width","Thickness"],
                    "Kick Plates" => ["Length","Width","Thickness"],
                    "Door Selectors" => ["Width","Height"],
                    "Panic Hardware" => ["Max Door Height","Max Door Width","Height","Width"],
                    "Door security viewer" => ["Cut out diameter","Outer lens diameter"],
                    "Morticed drop down seals" => ["Length","Height","Width","Acoustic rating","Min cut length"],
                    "Face fixed drop seals" => ["Length","Height","Width","Acoustic rating","Min cut length"],
                    "Threshold Seal" => ["Length","Height","Width"],
                    "Air transfer grills" => ["Length","Height","Width"],
                    "Letterplates" => ["Overall Length","Overall Height","Aperture Length","Aperture Height","Fixing bolt centres"],
                    "Cable Ways" => ["Height","Width"],
                    "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
                    "Lever Handle" => ["Rose Width","Rose Thickness","Lever Length"],
                    "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
                    "Door Signage" => ["Length","Height"],
                    "Face Fixed Door Closers" => ["Width","Height","Depth","Width","Min Door Width","Min Door Height","Min Door Thickness"],
                    "Thumbturn" => ["Length","Thickness"],
                    "Keyhole Escutcheon" => ["Length","Thickness"],
                ];
                $CategoryFieldsJSON = [];
                foreach($categoryArray as $index => $val){
                    foreach($val as $indexInner => $valInner){
                        $dynamicFieldKey =  preg_replace('/\s+/', '', $index.$valInner);
                        $CategoryFieldsJSON[$dynamicFieldKey] = $request->$dynamicFieldKey;
                    }
                }

                if($request->hasFile('Image')){
                    $file = $request->file('Image');
                    $ImageName = rando().$file->getClientOriginalName();
                    $filepath = public_path('uploads/IronmongeryInfo/');
                    $ImageExtension = $file->getClientOriginalExtension();
                    if(!in_array($ImageExtension,["jpg", "jpeg", "png", "jpg", "JPG", "JPEG", "PNG"])){
                        return redirect('ironmongery-info/create');die;
                    }
                    
                    $file->move($filepath,$ImageName);
                }

                if($request->hasFile('PdfSpecification')){
                    $file = $request->file('PdfSpecification');
                    $PdfSpecificationName = rando().$file->getClientOriginalName();
                    $filepath = public_path('uploads/IronmongeryInfo/');
                    $PdfSpecificationExtension = $file->getClientOriginalExtension();
                    if(!in_array($PdfSpecificationExtension,["pdf", "PDF"])){
                        return redirect('ironmongery-info/create');die;
                    }
                    
                    $file->move($filepath,$PdfSpecificationName);
                }

                $count = count($request->FireRating);
                $i = 0;
                // while($count > $i){
                    $firerating = $request->FireRating[$i];

                    $Finishescount = count($request->Finishes);
                    $k = $Finishescount - 1;
                    $j = 0;
                    $Finishes = '';
                    while($Finishescount > $j){
                        if($k == $j){
                            $Finishes .= $request->Finishes[$j];
                        } else {
                            $Finishes .= $request->Finishes[$j].",";
                        }
                        
                    $j++;
                    }

                    $IronmongeryInfo = new IronmongeryInfoModel();
                    // $IronmongeryInfo->configurableitems = $request->configurableitems;
                    $IronmongeryInfo->UserId = Auth::user()->id;
                    $IronmongeryInfo->GeneratedKey = rando();
                    $IronmongeryInfo->Image = $ImageName;
                    $IronmongeryInfo->Category = $request->Category;
                    $IronmongeryInfo->CategoryFieldsJSON = json_encode($CategoryFieldsJSON);
                    $IronmongeryInfo->Name = $request->Name;
                    $IronmongeryInfo->Code = $request->Code;
                    // $IronmongeryInfo->Dimensions = $request->Dimensions;
                    $IronmongeryInfo->Description = $request->Description;
                    $IronmongeryInfo->Finishes = $Finishes;
                    $IronmongeryInfo->FireRating = implode(",",$request->FireRating);
                    // $IronmongeryInfo->FireRating = $firerating;
                    $IronmongeryInfo->FireCartNoUK = $request->FireCartNoUK;
                    $IronmongeryInfo->FireCartNoEU = $request->FireCartNoEU;
                    $IronmongeryInfo->Price = $request->Price;

                    // tkt-485 task
                    $IronmongeryInfo->staticWidth = $request->staticWidth;
                    $IronmongeryInfo->staticHeight = $request->staticHeight;
                    $IronmongeryInfo->staticDepth = $request->staticDepth;
                    $IronmongeryInfo->distanceFromBottomOfDoor = $request->distanceFromBottomOfDoor;
                    $IronmongeryInfo->distanceFromLeadingEdgeOfDoor = $request->distanceFromLeadingEdgeOfDoor;
                    $IronmongeryInfo->centered = $request->centered;
                    //end

                    $IronmongeryInfo->intumescentseal_fd30 = $request->intumescentseal_fd30;
                    $IronmongeryInfo->intumescentseal_fd30_price = $request->intumescentseal_fd30_price;

                    $IronmongeryInfo->intumescentseal_fd60 = $request->intumescentseal_fd60;
                    $IronmongeryInfo->intumescentseal_fd60_price = $request->intumescentseal_fd60_price;

                    $IronmongeryInfo->ManMinutes = $request->ManMinutes;
                    $IronmongeryInfo->MachineMinutes = $request->MachineMinutes;

                    $IronmongeryInfo->Supplier = $request->Supplier;
                    $IronmongeryInfo->PdfSpecification = $PdfSpecificationName;
                    $IronmongeryInfo->Status = $request->Status;
                    $IronmongeryInfo->save();

                    $i++;
                // }
                // dd($IronmongeryInfo);
                if(auth::user()->UserType != 1){
                    $selectedIronMongry = new SelectedIronmongery();
                    $selectedIronMongry->ironmongery_id = $IronmongeryInfo->id;
                    $selectedIronMongry->UserId = auth()->user()->id;
                    $selectedIronMongry->save();
                }

                $IronmongeryInfoId = $IronmongeryInfo->id;

                $updateId = IronmongeryInfoModel::find($IronmongeryInfoId);
                $updateId->IronmongeryId = $IronmongeryInfoId;
                $updateId->save();

                if(!empty( $IronmongeryInfoId)){
                    // return redirect('ironmongery-info/records');
                    return redirect()->back()->with('success', 'The Ironmongery Info inserted successfully!');
                }
            }
        }else{
            return redirect()->back()->with('error', 'Please fill required field!');
        }

        return null;
    }


    public function records($GeneratedKey){

        $user = Auth::user();

        $UserId = [$user->id];

        $ConfigurableItems = ConfigurableItems::get();
        if($GeneratedKey == 0){
            $data = IronmongeryInfoModel::whereIn('UserId', $UserId)->orderBy('Category','ASC')->orderBy('id','desc')->get();
        }else{
            $data = IronmongeryInfoModel::whereIn('UserId', $UserId )->where(['GeneratedKey' => $GeneratedKey])->orderBy('Category','ASC')->orderBy('id','desc')->get();
        }
        
        if(Auth::user()->id==1){
            $currency = '';
        }else{
            $SettingCurrency = SettingCurrency::whereIn('UserId',$UserId)->get()->first();
            $currency = empty($SettingCurrency) ? "£" : QuotationCurrency($SettingCurrency['currency']);
        }

        $IronmongeryName = IronmongeryName::where('status',1)->orderby('category','asc')->get();
        foreach ($IronmongeryName as $val){
            $categoryArray[$val->name] = $val->field_list;
        }

        return view('IronmongeryInfo.IronmongeryInfoList',['data' => $data, 'ConfigurableItems' => $ConfigurableItems, 'currency' => $currency, 'categoryArray' => $categoryArray]);
    }

    public function IronmongeryExport(Request $request){
        return Excel::download(new IronmongeryInfoExport(), 'Ironmongery-List.xlsx');
    }

    public function IronmongeryTableInsert(Request $request): void{
        $data = IronmongeryInfoModel::whereNull('IronmongeryId')->get();
        foreach($data as $val){
            $save = IronmongeryInfoModel::find($val->id);
            $save->IronmongeryId = $val->id;
            $save->save();
        }
    }

    public function IronmongeryImport(Request $request){

        $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
        $i = 0;
        foreach ($data[0] as $row) {

            if ($i == 0) {
                $i++;
                continue;
            }
            
            $j = 0;
            $sno = trim($row[$j++]);
            $id = trim($row[$j++]);
            $FireRating = trim($row[$j++]);
            $Category = trim($row[$j++]);
            $Name = trim($row[$j++]);
            $Code = trim($row[$j++]);
            // $Dimensions = trim($row[$j++]);
            $Description = trim($row[$j++]);
            $Supplier = trim($row[$j++]);
            $Price = trim($row[$j++]);
            $i++;

            $ironmongeryData = IronmongeryInfoModel::where(['id' => $id,'FireRating' => $FireRating,'Category' => $Category,'Name' => $Name,'Code' => $Code,'UserId' => Auth::user()->id])->get()->first();

            if(!empty($ironmongeryData)){
                $IronmongeryInfoModel = IronmongeryInfoModel::find($ironmongeryData->id);
                $IronmongeryInfoModel->Price = $Price;
                $IronmongeryInfoModel->save();
            }
        }
        
        $msg = '<p>Excel file is imported successfully.</p>';
        return redirect()->back()->with('success', $msg);
    }

    public function list($IronmongeryKey=null){
        $UserId = Auth::user()->id;
        $ConfigurableItems = ConfigurableItems::get();
        if(Auth::user()->id==1){
            $data = IronmongeryInfoModel::where([ 'UserId' => $UserId ])->orderBy('id','desc')->get();
            $currency = "£";
        }else{
            $data = IronmongeryInfoModel::wherein('UserId' , [1])->orderBy('id','desc')->get();
            $SettingCurrency = SettingCurrency::where('UserId',Auth::user()->id)->get()->first();
            $currency = empty($SettingCurrency) ? "£" : QuotationCurrency($SettingCurrency['currency']);

        }
        
        $list = '';
        $ConfigurableItems = ConfigurableItems::get();

        $IronmongeryName = IronmongeryName::where('status',1)->orderby('category','asc')->get();
        foreach ($IronmongeryName as $val){
            $categoryArray[$val->name] = $val->field_list;
        }

        // $categoryArray = array(
        //     "Hinges" => ["Width","Height","Thickness","Knuckle Diameter"],
        //     "Floor Spring" => ["Length","Width","Height"],
        //     "Locks and Latches" => ["Case Depth","Backset"],
        //     "Flush Bolts" => ["Length","Width","Mortice Depth"],
        //     "Concealed Overhead Closer" => ["Width","Height","Depth","Min Door Width","Min Door Height","Min Door Thickness"],
        //     "Pull Handles" => ["Length","Fixing hole centres","Projection"],
        //     "Push Handles" => ["Length","Width","Thickness"],
        //     "Kick Plates" => ["Length","Width","Thickness"],
        //     "Door Selectors" => ["Width","Height"],
        //     "Panic Hardware" => ["Max Door Height","Max Door Width","Height","Width"],
        //     "Door security viewer" => ["Cut out diameter","Outer lens diameter"],
        //     "Morticed drop down seals" => ["Length","Height","Width","Acoustic rating","Min cut length"],
        //     "Face fixed drop seals" => ["Length","Height","Width","Acoustic rating","Min cut length"],
        //     "Threshold Seal" => ["Length","Height","Width"],
        //     "Air transfer grills" => ["Length","Height","Width"],
        //     "Letterplates" => ["Overall Length","Overall Height","Aperture Length","Aperture Height","Fixing bolt centres"],
        //     "Cable Ways" => ["Height","Width"],
        //     "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
        //     "Lever Handle" => ["Rose Width","Rose Thickness","Lever Length"],
        //     "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
        //     "Door Signage" => ["Length","Height"],
        //     "Face Fixed Door Closers" => ["Width","Height","Depth","Width","Min Door Width","Min Door Height","Min Door Thickness"],
        //     "Thumbturn" => ["Length","Thickness"],
        //     "Keyhole Escutcheon" => ["Length","Thickness"],
        //     "Door Stops" => ["Length","Height","Diameter"],
        //     "Cylinders" => ["Length","Width","Depth","Number of Pins","Security","Cylinder Type"," Number of Keys","Keying Function"],
        // );

        if(isset($IronmongeryInfo->CategoryFieldsJSON)){
            $categoryFieldsArray = json_decode($IronmongeryInfo->CategoryFieldsJSON);
        }


        foreach(array_keys($categoryArray) as $categoryIndex){
            $categoryIndexWithoutSpace = preg_replace('/\s+/', '', $categoryIndex);
            $list .= '<div class="question">
                <header>
                    <h3>';
            if($categoryIndex == 'Push Handles'){
                $list .= 'Push Plates';
            }else{
                $list .= $categoryIndex;
            }
            
            if($categoryIndex == 'Air Transfer Grill'){
                $categoryIndexWithoutSpace = "Airtransfergrills";
            }
            
            if($categoryIndex == 'Keyhole Escutche'){
                $categoryIndexWithoutSpace = "KeyholeEscutcheon";
            }
            
            if($categoryIndex == 'Locks And Latches'){
                $categoryIndexWithoutSpace = "LocksandLatches";
            }
            
            if($categoryIndex == 'Face Fixed Door Closer'){
                $categoryIndexWithoutSpace = "FaceFixedDoorClosers";
            }
            
            if($categoryIndex == 'Push Plates'){
                $categoryIndexWithoutSpace = "PushHandles";
            }
            
            $list .='</h3><i class="fa fa-chevron-down"></i></header><main><ul class="accordian_list"><div class="row">';
            if(!empty($data) && (array)$data !== []){
                foreach($data as $row){
                    if($row->Category == $categoryIndexWithoutSpace){
                        $select = SelectedIronmongery::where(['UserId' => auth()->user()->id, 'ironmongery_id' => $row->id])->count();
                        $selected = $select > 0 ? 'border-success' : null;
                        
                        $list .= '<div class="col-md-3 col-sm-6 col-6" onClick="selectMe('.$row->id.')">
                                    <div class="product_holder '.$selected.' select_class_'.$row->id.'">
                                        <div class="product_img">
                                            <img src="'.url('uploads/IronmongeryInfo/'.$row->Image).'">
                                        </div>
                                        <a class="product_name" href="#"><span>'.$row->Code.'-</span>'.$row->Name.'</a>
                                        <div class="product_face">
                                            <b>'.$row->FireRating.'</b>
                                            <b>'.$currency.$row->Price.'</b>
                                            <b>'.$row->Category.'</b>
                                        </div>
                                    </div>
                                </div>';
                    }
                }
            }
            
            $list .=   '</div></ul></main></div>';

        }

        return view('IronmongeryInfo.SelectedIronmongeryList',['list' => $list, 'currency' => $currency]);
    }

    public function select(request $request): void{
        $pageId = $request->pageId;
        if(!empty($request->iron_id)){
            $isExist = SelectedIronmongery::where(['UserId' => auth()->user()->id])
            ->where('ironmongery_id',$request->iron_id)
            ->orderBy('id','desc')
            ->first();

            if(count((array)$isExist)==0){
                $selectedIronMongry = new SelectedIronmongery();
                $selectedIronMongry->ironmongery_id = $request->iron_id;
                $selectedIronMongry->UserId = auth()->user()->id;
                $selectedIronMongry->user_id = auth()->user()->id;

                $selectedIronMongry->save();

                $get_ironmongery = IronMongeryInfoModel::where('id',$request->iron_id)->first();
                $IronmongeryInfo = new IronmongeryInfoModel();
                // $IronmongeryInfo->configurableitems = $request->configurableitems;
                $IronmongeryInfo->UserId = Auth::user()->id;
                $IronmongeryInfo->GeneratedKey = $get_ironmongery->GeneratedKey;
                $IronmongeryInfo->Image = $get_ironmongery->Image;
                $IronmongeryInfo->Category = $get_ironmongery->Category;
                $IronmongeryInfo->CategoryFieldsJSON = $get_ironmongery->CategoryFieldsJSON;
                $IronmongeryInfo->Name = $get_ironmongery->Name;
                $IronmongeryInfo->Code = $get_ironmongery->Code;
                // $IronmongeryInfo->Dimensions = $get_ironmongery->Dimensions;
                $IronmongeryInfo->Description = $get_ironmongery->Description;
                $IronmongeryInfo->Finishes = $get_ironmongery->Finishes;
                $IronmongeryInfo->FireRating = $get_ironmongery->FireRating;
                $IronmongeryInfo->FireCartNoUK = $get_ironmongery->FireCartNoUK;
                $IronmongeryInfo->FireCartNoEU = $get_ironmongery->FireCartNoEU;
                $IronmongeryInfo->Price = $get_ironmongery->Price;
                $IronmongeryInfo->Supplier = $get_ironmongery->Supplier;
                $IronmongeryInfo->PdfSpecification = $get_ironmongery->PdfSpecification;
                $IronmongeryInfo->Status = $get_ironmongery->Status;
                $IronmongeryInfo->IronmongeryId = $get_ironmongery->id;
                $IronmongeryInfo->staticHeight = $get_ironmongery->staticHeight;
                $IronmongeryInfo->staticWidth = $get_ironmongery->staticWidth;
                $IronmongeryInfo->staticDepth = $get_ironmongery->staticDepth;
                $IronmongeryInfo->distanceFromBottomOfDoor = $get_ironmongery->distanceFromBottomOfDoor;
                $IronmongeryInfo->distanceFromLeadingEdgeOfDoor = $get_ironmongery->distanceFromLeadingEdgeOfDoor;
                $IronmongeryInfo->centered = $get_ironmongery->centered;
                $IronmongeryInfo->save();


                $selectedIronMongryId = $selectedIronMongry->id;
                if(!empty($selectedIronMongryId)){
                   echo json_encode(['status'=>'ok','msg'=>'records is selected']);
                }else{
                    echo json_encode(['status'=>'error','msg'=>'']);
                }
            }else{
                $selectedIronMongryId = SelectedIronmongery::where([ 'ironmongery_id' => $request->iron_id])
                ->where('UserId', auth()->user()->id)
                ->delete();
                IronmongeryInfoModel::where([ 'IronmongeryId' => $request->iron_id])
                ->where('UserId', auth()->user()->id)
                ->delete();
                if(!empty($selectedIronMongryId)){
                   echo json_encode(['status'=>'deleted','msg'=>'records is deleted']);
                }else{
                    echo json_encode(['status'=>'error','msg'=>'']);
                }
            }

        }

    }

    public function filterIronMongeryFilter(request $request): void{

        // if (Auth::user()->UserType == 2) {
        // $myAdminGroup = getMyCreatedAdmins();
        // $useTbl = $myAdminGroup;
        // $useTbl = array_merge(['1'], $myAdminGroup);
        // }else{

            $user = auth()->user();

            $useTbl = [$user->id];
        // }

       // $useTbl = auth()->user();
        $userType = auth()->user()->UserType;
        $currency = '';
        if($userType=="1" ||$userType=="4"){
            $data = IronmongeryInfoModel::join('selected_ironmongery','selected_ironmongery.ironmongery_id','ironmongery_info.id')
                                        ->where('ironmongery_info.Category',$request->ironCategoryType)->orderBy('ironmongery_info.id','desc')->get();
        }else{

            $data = IronmongeryInfoModel::join('selected_ironmongery','selected_ironmongery.ironmongery_id','ironmongery_info.id')
                                        ->wherein( 'selected_ironmongery.UserId', $useTbl )
                                        ->orderBy('ironmongery_info.Category','ASC')->orderBy('ironmongery_info.id','desc')
                                        ->get();

            // $data = IronmongeryInfoModel::wherein('UserId',$useTbl)->orderBy('id','desc')->get();


            $SettingCurrency = SettingCurrency::wherein('UserId',$useTbl)->get()->first();
            if(!empty($SettingCurrency)){
                $currency = QuotationCurrency($SettingCurrency['currency']);
            }

        }
        
        if(count((array)$data)!="0"){
            echo json_encode(["status"=>"ok","data"=>$data,"currency"=>$currency]);
        }else{
            echo json_encode(["status"=>"error","data"=>'']);
        }
    }




        public function delete(request $request, $id): string{
            if (Auth::user()->UserType == 2) {
                $myAdminGroup = getMyCreatedAdmins();
                $useTbl = $myAdminGroup;
                // $useTbl = array_merge(['1'], $myAdminGroup);
                }else{

                    $user = auth()->user();

                    $useTbl = [$user->id];
                }
            
            $iron_mongery_info = IronmongeryInfoModel::where('id',$id)->first();
            $selected_iron_mongery = SelectedIronmongery::where('ironmongery_id',$id)->get();
            $selected_iron_mongery_id = [];
            if($selected_iron_mongery){
            foreach($selected_iron_mongery as $value){
                $selected_iron_mongery_id[] = $value->id;
            }

            $count = count($selected_iron_mongery_id);

            for($i=0; $i<$count; $i++){
                $data = AddIronmongery::where($request->category,$selected_iron_mongery_id[$i])->first();
                $category = $request->category;
                $qty = $request->qty;
                if($data){
                $data->$category = null;
                $data->$qty = null;
                $data->save();
                }
            }

            IronmongeryInfoModel::where('id',$id)->whereIn('UserId',$useTbl)->delete();
            SelectedIronmongery::where('ironmongery_id',$iron_mongery_info->IronmongeryId)
            ->whereIn('UserId',$useTbl)->delete();
            }

            return 'success';
        }





}
