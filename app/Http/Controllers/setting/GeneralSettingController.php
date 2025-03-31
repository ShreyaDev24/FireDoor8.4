<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\SettingCurrency;
use App\Models\CompanyQuotationCounter;
use App\Models\CompanyOrderCounter;
use App\Models\DoorFrameConstruction;


class GeneralSettingController extends Controller
{
    public function generalSetting()
    {
        if(Auth::user()->UserType== 2){
            $users = getMyCreatedAdmins();
        }else{
            $users = [Auth::user()->id];
        }
        $currency = SettingCurrency::wherein('UserId',$users)->first();
        $ComQuotCounter = CompanyQuotationCounter::wherein('UserId',$users)->first();
        $ComOrdCounter = CompanyOrderCounter::wherein('UserId',$users)->first();
        return view('Setting.generalsetting',compact('currency','ComQuotCounter','ComOrdCounter'));
    }
    public function DoorFrameConstruction(Request $request)
    {
        $users = Auth::user()->id;
        // $doorFrameConstruction = DoorFrameConstruction::where('UserId',$users)->get();
        $half_lap_joint = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();
        $mitre_joint = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Mitre_Joint')->first();
        $mortice_tenon_joint = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Mortice_&_Tenon_Joint')->first();
        $butt_joint = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Butt_Joint')->first();
        $hinge_location = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Hinge_Location')->first();
        return view('Setting.DoorFramConstruction', compact('users','half_lap_joint','mitre_joint','mortice_tenon_joint','butt_joint','hinge_location'));
    }

    public function storeDoorFrameConstruction(Request $request)
    {
        $userId = Auth::user()->id;
        $doorFrames = [
            'Half_Lapped_Joint' => ['width' => $request->input('width_half_lap'), 'height' => $request->input('height_half_lap')],
            'Mitre_Joint' => ['width' => $request->input('width_mitre'), 'height' => $request->input('height_mitre')],
            'Mortice_&_Tenon_Joint' => ['width' => $request->input('width_mortice'), 'height' => $request->input('height_mortice')],
            'Butt_Joint' => ['width' => $request->input('width_butt'), 'height' => $request->input('height_butt')],
            'Hinge_Location' => ['hinge1Location' => $request->input('hinge1Location'), 'hinge2Location' => $request->input('hinge2Location'),'hinge3Location' => $request->input('hinge3Location'),'hingeCenterCheck' => $request->input('hingeCenterCheck')],
        ];
        $existDoorFrameConst = $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)->get();
        foreach ($doorFrames as $door => $dimensions){
            $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)
            ->where('DoorFrameConstruction', $door)
            ->first();

            if ($doorFrameConst) {
                if($door == 'Hinge_Location'){
                    $doorFrameConst->update([
                        'hinge1Location' => $dimensions['hinge1Location'],
                        'hinge2Location' => $dimensions['hinge2Location'],
                        'hinge3Location' => $dimensions['hinge3Location'],
                        'hingeCenterCheck' => $dimensions['hingeCenterCheck']
                    ]);
                }
                else{
                    $doorFrameConst->update([
                        'Width' => $dimensions['width'],
                        'Height' => $dimensions['height']
                    ]);
                }
            }else{
                $doorFrame = new DoorFrameConstruction;
                if($door == 'Hinge_Location'){
                    $doorFrame->hinge1Location = $dimensions['hinge1Location'];
                    $doorFrame->hinge2Location = $dimensions['hinge2Location'];
                    $doorFrame->hinge3Location = $dimensions['hinge3Location'];
                    $doorFrame->hingeCenterCheck = $dimensions['hingeCenterCheck'];
                }
                else{
                    $doorFrame->Width = $dimensions['width'];
                    $doorFrame->Height = $dimensions['height'];
                }
                $doorFrame->DoorFrameConstruction = $door;
                $doorFrame->UserId = $userId;
                $doorFrame->save();
            }
        }
        if (!empty($existDoorFrameConst)) {
            return redirect()->back()->with('success', 'Update Successfully!');
        }else{
            return redirect()->back()->with('success', 'Added Successfully!');
        }
    }

    public function subgeneralSetting(Request $request)
    {
        // $valid = $request->validate([
        //     'currency' => 'required'
        // ]);
        // $update_val = $request->updval;
         $valid = $request->validate([
            'currency' => 'required',
            'quotation_prefix' => 'required',
            'order_prefix' => 'required',
        ],
        [
           'currency.required' => 'The currency field is required.',
           'order_prefix.required' => 'The Order Prefix field is required.',
           'quotation_prefix.required' => 'The Quotation Prefix field is required.',
        ]);
        if(Auth::user()->UserType== 2){
            $users = getMyCreatedAdmins();
        }else{
            $users = [Auth::user()->id];
        }

        foreach ($users as $key => $usr) {
            if(isset($request->currencyUpdate) && isset($request->quotation_prefixUpdval) && isset($request->order_prefixUpdval)){
                if(isset($request->currencyUpdate)){
                    $currency = SettingCurrency::find($request->currencyUpdate);
                    $currency->UserId = Auth::user()->id;
                    $currency->currency = $request->currency;
                    $currency->HideCosts = $request->HideCosts ?? 0;
                    $currency->SetCurrencyRate = $request->SetCurrencyRate;
                    $currency->updated_at = date('Y-m-d H:i:s');
                    $currency->update();
                }
                if(isset($request->quotation_prefixUpdval)){
                    $quotation_prefix = CompanyQuotationCounter::find($request->quotation_prefixUpdval);
                    $quotation_prefix->UserId = Auth::user()->id;
                    $quotation_prefix->quotation_prefix = $request->quotation_prefix;
                    $quotation_prefix->order_prefix = $request->order_prefix;
                    $quotation_prefix->quotation_counter = 100001;
                    $quotation_prefix->updated_at = date('Y-m-d H:i:s');
                    $quotation_prefix->update();
                }

                if(isset($request->order_prefixUpdval)){
                    $order_prefix = CompanyOrderCounter::find($request->order_prefixUpdval);
                    $order_prefix->UserId = Auth::user()->id;
                    $order_prefix->order_prefix = $request->order_prefix;
                    $order_prefix->quotation_prefix = $request->quotation_prefix;
                    $order_prefix->order_counter = 100001;
                    $order_prefix->updated_at = date('Y-m-d H:i:s');
                    $order_prefix->update();
                }

                return redirect()->back()->with('success', 'Update Successfully!');
            }else{

                if(isset($request->currency)){
                    $a = new SettingCurrency;
                    $a->created_at = date('Y-m-d H:i:s');
                    $a->UserId = Auth::user()->id;
                    $a->currency = $request->currency;
                    $a->save();
                }

                if(isset($request->quotation_prefix)){
                    $b = new CompanyQuotationCounter;
                    $b->created_at = date('Y-m-d H:i:s');
                    $b->UserId = Auth::user()->id;
                    $b->quotation_prefix = $request->quotation_prefix;
                    $b->order_prefix = $request->order_prefix;
                    $b->quotation_counter = 100001;
                    $b->save();
                }

                if(isset($request->order_prefix)){
                    $c = new CompanyOrderCounter;
                    $c->created_at = date('Y-m-d H:i:s');
                    $c->UserId = Auth::user()->id;
                    $c->order_prefix = $request->order_prefix;
                    $c->quotation_prefix = $request->quotation_prefix;
                    $c->order_counter = 100001;
                    $c->save();
                }
            }
            if(isset($request->currencyUpdate) && isset($request->quotation_prefixUpdval) && isset($request->order_prefixUpdval)){
                return redirect()->back()->with('success', 'Update Successfully!');
            }else{
                return redirect()->back()->with('success', 'Added Successfully!');
            }
        // if(!is_null($update_val)){
        //     return redirect()->back()->with('success', 'The currency update successfully!');
        // }
        // else
        // {
        //     return redirect()->back()->with('success', 'The currency added successfully!');
        // }

        }
    }
}
