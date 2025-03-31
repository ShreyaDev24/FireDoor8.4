<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyOrderCounter;
use App\Models\CompanyQuotationCounter;

class QuotationPrefixController extends Controller
{
    public function QuotationPrefix()
    {
        $ComQuotCounter = CompanyQuotationCounter::where('UserId',Auth::user()->id)->first();
        return view('Setting.quotationprefix',compact('ComQuotCounter'));
    }

    public function OrderPrefix()
    {
        $ComOrdCounter = CompanyOrderCounter::where('UserId',Auth::user()->id)->first();
        return view('Setting.orderprefix',compact('ComOrdCounter'));
    }

    public function setprefix(Request $request)
    {
        $valid = $request->validate([
            'quotation_prefix' => 'required'
        ],
        [
           'quotation_prefix.required' => 'The Quotation Prefix field is required.',
        ]);
        
        if(Auth::user()->UserType== 2){
            $users = getMyCreatedAdmins();
            CompanyQuotationCounter::wherein('UserId', $users)->delete();
        }else{
            $users = [Auth::user()->id];
        }
        foreach ($users as $key => $usr) {
            $update_val = $request->updval;
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = CompanyQuotationCounter::find($update_val);
            } else {
                $a = new CompanyQuotationCounter;
                $a->created_at = date('Y-m-d H:i:s');
            }
            $a->UserId = $usr;
            $a->quotation_prefix = $request->quotation_prefix;
            $a->order_prefix = $request->order_prefix;
            $a->quotation_counter = 100001;
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'The Quotation Prefix update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'The Quotation Prefix added successfully!');
        }
    }

    public function set_order_prefix(Request $request)
    {
        $valid = $request->validate([
            'order_prefix' => 'required'
        ],
        [
           'order_prefix.required' => 'The Order Prefix field is required.',
        ]);
        $update_val = $request->updval;
        if(!is_null($update_val)){
            $a = CompanyOrderCounter::find($update_val);
        } else {
            $a = new CompanyOrderCounter;
            $a->created_at = date('Y-m-d H:i:s');
        }
        $a->UserId = Auth::user()->id;
        $a->order_prefix = $request->order_prefix;
        $a->order_counter = 100001;
        $a->updated_at = date('Y-m-d H:i:s');
        $a->save();
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'The Order Prefix update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'The Order Prefix added successfully!');
        }
    }
}
