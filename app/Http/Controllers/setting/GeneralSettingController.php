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
        return view('Setting.generalsetting',['currency' => $currency, 'ComQuotCounter' => $ComQuotCounter, 'ComOrdCounter' => $ComOrdCounter]);
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
        $Hinge_Frame_Location = DoorFrameConstruction::where('UserId',$users)->where('DoorFrameConstruction', 'Hinge_Frame_Location')->first();
        $allSettings = DoorFrameConstruction::where('UserId', $users)->get()->keyBy('DoorFrameConstruction');
        return view('Setting.DoorFramConstruction', ['users' => $users, 'half_lap_joint' => $half_lap_joint, 'mitre_joint' => $mitre_joint, 'mortice_tenon_joint' => $mortice_tenon_joint, 'butt_joint' => $butt_joint, 'hinge_location' => $hinge_location,'allSettings' => $allSettings,'Hinge_Frame_Location' => $Hinge_Frame_Location]);
    }

    public function storeDoorFrameConstruction(Request $request)
    {
        $userId = Auth::user()->id;

        $doorFrames = [
            // Primary Joint Types
            'Half_Lapped_Joint' => [
                'width' => $request->input('width_half_lap'),
                'height' => $request->input('height_half_lap'),
            ],
            'Mitre_Joint' => [
                'width' => $request->input('width_mitre'),
                'height' => $request->input('height_mitre'),
            ],
            'Mortice_&_Tenon_Joint' => [
                'width' => $request->input('width_mortice'),
                'height' => $request->input('height_mortice'),
            ],
            'Butt_Joint' => [
                'width' => $request->input('width_butt'),
                'height' => $request->input('height_butt'),
            ],

            // Hinge Configuration
            'Hinge_Location' => [
                'hinge1Location' => $request->input('hinge1Location'),
                'hinge2Location' => $request->input('hinge2Location'),
                'hinge3Location' => $request->input('hinge3Location'),
                'hingeCenterCheck' => $request->input('hingeCenterCheck'),
            ],

             // Hinge Configuration
            'Hinge_Frame_Location' => [
                'hingeFrameLocation1' => $request->input('hingeFrameLocation1'),
                'hingeFrameLocation2' => $request->input('hingeFrameLocation2'),
                'hingeFrameLocation3' => $request->input('hingeFrameLocation3'),
                'hingeFrameLocation4' => $request->input('hingeFrameLocation4'),
            ],

            // Plant-On Stop Settings
            'PlantOn' => [
                'HalfLipped' => [
                    'width' => $request->input('plantOn_halfLippedWidth'),
                    'height' => $request->input('plantOn_halfLippedHeight'),
                ],
                'Mitre' => [
                    'width' => $request->input('plantOn_mitreWidth'),
                    'height' => $request->input('plantOn_mitreHeight'),
                ],
                'Mortice1' => [
                    'width' => $request->input('plantOn_mortice1Width'),
                    'height' => $request->input('plantOn_mortice1Height'),
                ],
                'Butt' => [
                    'width' => $request->input('plantOn__buttWidth'),
                    'height' => $request->input('plantOn__buttHeight'),
                ],
            ],
            // Side Light Panels
            'SideLight' => [
                'HalfLipped' => [
                    'width' => $request->input('sideLight_halfLippedWidth'),
                    'height' => $request->input('sideLight_halfLippedHeight'),
                ],
                'Mitre' => [
                    'width' => $request->input('sideLight_mitreWidth'),
                    'height' => $request->input('sideLight_mitreHeight'),
                ],
                'Mortice1' => [
                    'width' => $request->input('sideLight_mortice1Width'),
                    'height' => $request->input('sideLight_mortice1Height'),
                ],
                'Butt' => [
                    'width' => $request->input('sideLight_buttWidth'),
                    'height' => $request->input('sideLight_buttHeight'),
                ],
            ],

            // Fanlight Panels
            'Fanlight' => [
                'HalfLipped' => [
                    'width' => $request->input('fanlightHalfLippedWidth'),
                    'height' => $request->input('fanlightHalfLippedHeight'),
                ],
                'Mitre' => [
                    'width' => $request->input('fanlightMitreWidth'),
                    'height' => $request->input('fanlightMitreHeight'),
                ],
                'Mortice1' => [
                    'width' => $request->input('fanlightMortice1Width'),
                    'height' => $request->input('fanlightMortice1Height'),
                ],
                'Butt' => [
                    'width' => $request->input('fanlight_buttWidth'),
                    'height' => $request->input('fanlight_buttHeight'),
                ],
            ],
            // Vision Panels
            'VisionPanel' => [
                'NRF' => [
                    'width' => $request->input('vpWidthNRF'),
                    'height' => $request->input('vpHeightNRF'),
                ],
                'FD60' => [
                    'width' => $request->input('vpWidthFD60'),
                    'height' => $request->input('vpHeightFD60'),
                ],
            ],

            // Side Lights
            'SideLightFD' => [
                'FD30' => [
                    'width' => $request->input('sideLightWidthFD30'),
                    'height' => $request->input('sideLightHeightFD30'),
                ],
                'FD60' => [
                    'width' => $request->input('sideLightWidthFD60'),
                    'height' => $request->input('sideLightHeightFD60'),
                ],
            ],

            // Fanlight Sizes
            'FanlightSize' => [
                'NRF' => [
                    'width' => $request->input('fanlightNrfWidth'),
                    'height' => $request->input('fanlightNrfHeight'),
                ],
                'FD60' => [
                    'width' => $request->input('fanlightFd60Width'),
                    'height' => $request->input('fanlightFd60Height'),
                ],
            ],

            // Vision Panel Beading
            'VPBead' => [
                'NRF' => [
                    'width' => $request->input('vpBeadNrfWidth'),
                    'height' => $request->input('vpBeadNrfHeight'),
                ],
                'FD60' => [
                    'width' => $request->input('vpBeadFd60Width'),
                    'height' => $request->input('vpBeadFd60Height'),
                ],
            ],

            // Side Light Beading
            'SideBead' => [
                'NRF' => [
                    'width' => $request->input('sideBeadNrfWidth'),
                    'height' => $request->input('sideBeadNrfHeight'),
                ],
                'FD60' => [
                    'width' => $request->input('sideBeadFd60Width'),
                    'height' => $request->input('sideBeadFd60Height'),
                ],
            ],

            // Fanlight Beading
            'FanlightBead' => [
                'NRF' => [
                    'width' => $request->input('fanlightNRFWidth'),
                    'height' => $request->input('fanlightNRFHeight'),
                ],
                'FD60' => [
                    'width' => $request->input('fanlightFD60Width'),
                    'height' => $request->input('fanlightFD60Height'),
                ],
            ],

            // Screen Construction Settings

            'ScreenConstruction' => [
                'FrameHead' => [
                    'width' => $request->input('frameHeadWidth'),
                    'height' => $request->input('frameHeadHeight'),
                ],
                'Transom' => [
                    'width' => $request->input('transomAdjustment'),
                ],
                'Mullion' => [
                    'width' => $request->input('mullionAdjustment'),
                ],

            ],
            'ScreenGlass' => [
                'NFR' => [
                    'width' => $request->input('screenGlassWidthNFR'),
                    'height' => $request->input('screenGlassHeightNFR'),
                ],
                'FD60' => [
                    'width' => $request->input('screenGlassWidthFD60'),
                    'height' => $request->input('screenGlassHeightFD60'),
                ],
            ],
            'ScreenBead' => [
                'NFR' => [
                    'width' => $request->input('screenBeadWidthNFR'),
                    'height' => $request->input('screenBeadHeightNFR'),
                ],
            ],
            'Architrave' => [
                'NFR' => [
                    'width' => $request->input('architraveWidth'),
                    'height' => $request->input('architraveHeight'),
                ],
            ],
        ];

        // Loop through and flatten if needed
        foreach ($doorFrames as $mainKey => $values) {
            // Handle nested subtypes like DoorFrame.Mitre
            if (is_array($values) && !isset($values['width']) && $mainKey !== 'Hinge_Location' && $mainKey !== 'Hinge_Frame_Location') {
                foreach ($values as $subKey => $dimensions) {
                    $key = "{$mainKey}.{$subKey}";

                    $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)
                        ->where('DoorFrameConstruction', $key)
                        ->first();

                    if ($doorFrameConst) {
                        $doorFrameConst->update([
                            'Width' => $dimensions['width'],
                            'Height' => $dimensions['height'] ?? 0
                        ]);
                    } else {
                        DoorFrameConstruction::create([
                            'UserId' => $userId,
                            'DoorFrameConstruction' => $key,
                            'Width' => $dimensions['width'] ?? 0,
                            'Height' => $dimensions['height'] ?? 0
                        ]);
                    }
                }
            }

            // Handle Hinge_Location
            elseif ($mainKey === 'Hinge_Location') {
                $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)
                    ->where('DoorFrameConstruction', $mainKey)
                    ->first();
                if ($doorFrameConst) {
                    $doorFrameConst->hinge1Location = $values['hinge1Location'];
                    $doorFrameConst->hinge2Location = $values['hinge2Location'];
                    $doorFrameConst->hinge3Location = $values['hinge3Location'];
                    $doorFrameConst->hingeCenterCheck = $values['hingeCenterCheck'];
                    $doorFrameConst->save();
                } else {
                    $doorFrame = new DoorFrameConstruction;
                    $doorFrame->DoorFrameConstruction = $mainKey;
                    if($mainKey === 'Hinge_Location'){
                        $doorFrame->hinge1Location = $values['hinge1Location'];
                        $doorFrame->hinge2Location = $values['hinge2Location'];
                        $doorFrame->hinge3Location = $values['hinge3Location'];
                        $doorFrame->hingeCenterCheck = $values['hingeCenterCheck'];
                    }
                    $doorFrame->UserId = $userId;
                    $doorFrame->save();
                }
            }
            elseif ($mainKey === 'Hinge_Frame_Location') {
                $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)
                    ->where('DoorFrameConstruction', $mainKey)
                    ->first();
                if ($doorFrameConst) {
                    $doorFrameConst->hinge1Location = $values['hingeFrameLocation1'];
                    $doorFrameConst->hinge2Location = $values['hingeFrameLocation2'];
                    $doorFrameConst->hinge3Location = $values['hingeFrameLocation3'];
                    $doorFrameConst->hingeCenterCheck = $values['hingeFrameLocation4'];
                    $doorFrameConst->save();
                } else {
                    $doorFrame = new DoorFrameConstruction;
                    $doorFrame->DoorFrameConstruction = $mainKey;
                    if($mainKey === 'Hinge_Frame_Location'){
                        $doorFrame->hinge1Location = $values['hingeFrameLocation1'];
                        $doorFrame->hinge2Location = $values['hingeFrameLocation2'];
                        $doorFrame->hinge3Location = $values['hingeFrameLocation3'];
                        $doorFrame->hingeCenterCheck = $values['hingeFrameLocation4'];
                    }
                    $doorFrame->UserId = $userId;
                    $doorFrame->save();
                }
            }

            // Handle flat types like Mitre_Joint
            else {
                $doorFrameConst = DoorFrameConstruction::where('UserId', $userId)
                    ->where('DoorFrameConstruction', $mainKey)
                    ->first();

                if ($doorFrameConst) {
                    $doorFrameConst->update([
                        'Width' => $values['width'],
                        'Height' => $values['height'] ?? 0
                    ]);
                } else {
                    DoorFrameConstruction::create([
                        'UserId' => $userId,
                        'DoorFrameConstruction' => $mainKey,
                        'Width' => $values['width'],
                        'Height' => $values['height'] ?? 0
                    ]);
                }
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

        foreach (array_keys($users) as $key) {
            if (isset($request->currencyUpdate, $request->quotation_prefixUpdval, $request->order_prefixUpdval)){
                if (isset($request->currencyUpdate)){
                    $currency = SettingCurrency::find($request->currencyUpdate);
                    $currency->UserId = Auth::user()->id;
                    $currency->currency = $request->currency;
                    $currency->HideCosts = $request->HideCosts ?? 0;
                    $currency->SetCurrencyRate = $request->SetCurrencyRate;
                    $currency->updated_at = date('Y-m-d H:i:s');
                    $currency->update();
                }

                if (isset($request->quotation_prefixUpdval)){
                    $quotation_prefix = CompanyQuotationCounter::find($request->quotation_prefixUpdval);
                    $quotation_prefix->UserId = Auth::user()->id;
                    $quotation_prefix->quotation_prefix = $request->quotation_prefix;
                    $quotation_prefix->order_prefix = $request->order_prefix;
                    $quotation_prefix->quotation_counter = 100001;
                    $quotation_prefix->updated_at = date('Y-m-d H:i:s');
                    $quotation_prefix->update();
                }


                if (isset($request->order_prefixUpdval)){
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

                if (isset($request->currency)){
                    $a = new SettingCurrency;
                    $a->created_at = date('Y-m-d H:i:s');
                    $a->UserId = Auth::user()->id;
                    $a->currency = $request->currency;
                    $a->save();
                }

                if (isset($request->quotation_prefix)){
                    $b = new CompanyQuotationCounter;
                    $b->created_at = date('Y-m-d H:i:s');
                    $b->UserId = Auth::user()->id;
                    $b->quotation_prefix = $request->quotation_prefix;
                    $b->order_prefix = $request->order_prefix;
                    $b->quotation_counter = 100001;
                    $b->save();
                }

                if (isset($request->order_prefix)){
                    $c = new CompanyOrderCounter;
                    $c->created_at = date('Y-m-d H:i:s');
                    $c->UserId = Auth::user()->id;
                    $c->order_prefix = $request->order_prefix;
                    $c->quotation_prefix = $request->quotation_prefix;
                    $c->order_counter = 100001;
                    $c->save();
                }
            }

            if (isset($request->currencyUpdate, $request->quotation_prefixUpdval, $request->order_prefixUpdval)){
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

        return null;
    }
}
