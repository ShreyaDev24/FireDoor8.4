<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\SettingOMmanualIntro;
use App\Models\SettingOMmanualArchIron;
use App\Models\SettingOMmanualDoorFurniture;

class OMmanualSEttingController extends Controller
{
    public function settingOMmanual()
    {
        $id = Auth::user()->id;  
        $pdf1 = SettingOMmanualIntro::where('UserId',$id)->first();
        $pdf2 = SettingOMmanualArchIron::where('UserId',$id)->first();
        $pdf3 = SettingOMmanualDoorFurniture::where('UserId',$id)->first();
        return view('Setting.settingOMmanual',['pdf1' => $pdf1, 'pdf2' => $pdf2, 'pdf3' => $pdf3]);
    }
    
    public function submitIntroductionPDF(Request $request)
    {
        $valid = $request->validate([
            'editor1' => 'required'            
        ]);
        $update_val = $request->updval;
        if(!is_null($update_val)){
            $data = SettingOMmanualIntro::find($update_val);
        } else {
            $data = new SettingOMmanualIntro;
        }
        
        $UserId = Auth::user()->id;
        if($request->hasFile('backgroundImage')){
            $this->validate( $request,[
                'backgroundImage' => 'mimes:jpeg,png,jpg |max:1096',
            ]);
            $file = $request->file('backgroundImage');
            $name = time().$file->getClientOriginalName();
            $filepath = public_path('ommanual/');

            $path = $file;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $filedata = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);

            $file->move($filepath,$name);

            if(property_exists($request, 'update') && $request->update !== null){
                File::delete($filepath.$data->backgroundImage);
            }
            
            $data->backgroundImagebase64 = $base64;
            $data->backgroundImage = $name;
        }
        
        $data->UserId = $UserId;
        $data->content = $request->editor1;
        $data->created_at = date('Y-m-d H:i:s'); 
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Introduction PDF Format update successfully!');
        } else {
            return redirect()->back()->with('success', 'Introduction PDF Format added successfully!');	
        }
    }
    
    public function submitArchitecIronmon(Request $request)
    {
        $valid = $request->validate([
            'editor2' => 'required'            
        ]);
        $update_val = $request->updval2;
        if(!is_null($update_val)){
            $data = SettingOMmanualArchIron::find($update_val);
        } else {
            $data = new SettingOMmanualArchIron;
        }
        
        $UserId = Auth::user()->id;
        $data->UserId = $UserId;
        $data->content = $request->editor2;
        $data->created_at = date('Y-m-d H:i:s'); 
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Architectural Ironmongery update successfully!');
        } else {
            return redirect()->back()->with('success', 'Architectural Ironmongery added successfully!');	
        }
    }

    public function submitDoorFurniture(Request $request)
    {
        $valid = $request->validate([
            'editor3' => 'required'            
        ]);
        $update_val = $request->updval3;
        if(!is_null($update_val)){
            $data = SettingOMmanualDoorFurniture::find($update_val);
        } else {
            $data = new SettingOMmanualDoorFurniture;
        }
        
        $UserId = Auth::user()->id;
        $data->UserId = $UserId;
        $data->content = $request->editor3;
        $data->created_at = date('Y-m-d H:i:s'); 
        $data->updated_at = date('Y-m-d H:i:s');
        $data->save();

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Architectural Ironmongery update successfully!');
        } else {
            return redirect()->back()->with('success', 'Architectural Ironmongery added successfully!');	
        }  
    }
}
