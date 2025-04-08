<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\NonConfigurableItems;
use App\Models\NonConfigurableItemStore;
use DB;
use URL;
use Hash;
use Session;
use Mail;

class NonConfiguralItems extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $uIds = CompanyUsers();
        $item = NonConfigurableItems::wherein('userId',$uIds)->where('status', 1)->get();
        return view('NonConfigurableItem.index', ['item' => $item]);
    }

    public function create()
    {
        return view('NonConfigurableItem.CreateNonConfigurableItem');
    }
    
    public function edit($id)
    {
        if (!empty($id)) {
            $editdata = NonConfigurableItems::where('id', $id)->where('status', 1)->first();
            return view('NonConfigurableItem.CreateNonConfigurableItem', ['editdata' => $editdata]);
        } else {
            return redirect()->route('NonConfigurableItem/list');
        }
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $productCode = $request->product_code;
        $unit = $request->unit;
        $price = $request->price;

        if (!empty($name) && !empty($productCode) && !empty($unit) && !empty($price) && !empty($description)) {

            if (!empty($request->id)) {
                $data = NonConfigurableItems::find($request->id);
            } else {
                $data = new NonConfigurableItems();
            }

            if($request->hasFile('image')) {
                $this->validate($request, [
                    'image' => 'mimes:jpeg,png,jpg |max:1096',
                ]);
                $file = $request->file('image');
                $imageName = time() . $file->getClientOriginalName();
                $filepath = public_path('NonConfigImg/');
                $path = $file;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $filedata = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);
                $file->move($filepath, $imageName);
                if (property_exists($request, 'id') && $request->id !== null) {
                    File::delete($filepath . $data->NonconfiBase64);
                }
                
                $data->NonconfiBase64 = $base64;
                $data->image = $imageName;
            }

            $data->name = $name;
            $data->description = $description;
            $data->product_code = $productCode;
            $data->unit = $unit;
            $data->price = $price;
            $data->userId = Auth::user()->id;
            $data->save();

            if (!empty($request->id)) {
                $request->session()->flash('success', "Non Configurable Item updated successfully!");
            return redirect('non-configural-items/list')->with('Non Configurable Item updated successfully!');
            } else {
                $request->session()->flash('success', "Non Configurable Item Added successfully!");
            return redirect('non-configural-items/list')->with('Non Configurable Item Added successfully!');
            }
        } else {
            $request->session()->flash('error', "Something went wrong!");
            return redirect('non-configural-items/create')->with('Something went wrong!');
        }
    }

    public function delete($id)
    {
        if (!empty($id)) {
            NonConfigurableItems::where('userId', Auth::user()->id)->where('id', $id)->where('status', 1)->delete();
            return redirect()->route('non-configural-items/list')->with('success','Non Configurable Item deleted successfully!');
        }else{
            return redirect()->route('non-configural-items/list')->with('error','Something went wrong!');
        }
    }


    public function nonConfigstore(Request $request)
    {
        $quotationId = $request->QuotationId;
        $versionId = $request->versionId;
        $nonConfigurableId = $request->nonConfigurableId;
        $price = $request->price;
        $quantity = $request->quantity;
        $margin = discountQuotationValue($quotationId,$versionId);
        if($margin != 0){
            $QuoteSummaryDiscountValue = ($price * $margin) / 100;
            $price = ($margin > 0)? ($price + $QuoteSummaryDiscountValue): ($price - $QuoteSummaryDiscountValue);

        }
        
        $total_price = $quantity * $price;
        $currencyPrice = getCurrencyRate($quotationId);

        $data = new NonConfigurableItemStore();
        $data->quotationId = $quotationId;
        $data->versionId = $versionId;
        $data->nonConfigurableId = $nonConfigurableId;
        $data->price = $price * $currencyPrice;
        $data->quantity = $quantity;
        $data->total_price = $total_price * $currencyPrice;
        $data->userId = user_id();
        $data->save();

        if (!empty($data->id)) {
            $response = [
                'status'=>true,
                'msg'=> 'Non Configurable item added successfully!'
            ];
        } else {
            $response = [
                'status'=>false,
                'msg'=> 'something went wrong!'
            ];
        }
        
        return response()->json($response, 200,['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function nonConfigUpdate(Request $request){
        if(!empty($request->id)){
            $data = NonConfigurableItemStore::find($request->id);
            $data->quantity = $request->quantity;
            $margin = discountQuotationValue($data->quotationId,$data->versionId);
            $NonConfigurableItems = NonConfigurableItems::where('id',$data->nonConfigurableId)->first();
            if($margin != 0){
                $QuoteSummaryDiscountValue = ($NonConfigurableItems->price * $margin) / 100;
                $data->price = ($margin > 0)? ($NonConfigurableItems->price + $QuoteSummaryDiscountValue): ($NonConfigurableItems->price - $QuoteSummaryDiscountValue);

            }
            
            $data->total_price = $data->price * $request->quantity;
            $data->save();
            if (!empty($data->id)) {
                $response = [
                    'status'=>true,
                    'msg'=> 'Non Configurable item updated successfully!'
                ];
            } else {
                $response = [
                    'status'=>false,
                    'msg'=> 'something went wrong!'
                ];
            }
        }else{
            $response = [
                'status'=>false,
                'msg'=> 'something went wrong!'
            ];
        }

        return response()->json($response, 200,['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function nonConfigDelete(Request $request): string{
        $data = NonConfigurableItemStore::find($request->id);
        $data->delete();
        return 'success';
    }
}
