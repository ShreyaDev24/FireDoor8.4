<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;
use URL;
use Hash;
use Session;
use Mail;

use App\Models\Architect;
use App\Models\User;

class ArchitectController extends Controller
{
public function __construct()
{
    $this->middleware('auth');
}

public function add()
{
    if(Auth::user()->UserType=='1' ){
        return view('Architects.AddArchitect');
    }

    return null;
}

    public function store(request $request)
    {
        if(property_exists($request, 'update') && $request->update !== null){
            $data = Architect::where('UserId',$request->update)->first();
            $user = User::where('id',$request->update)->first();
            $flash = "updated";
        } else {
            $data = new Architect();
            $user = new User();
            $flash = "added";
            $this->validate( $request,[
                'UserEmail'=> "required|email|unique:users",
            ]);
        }

        if(!empty($request->CompanyName) && !empty($request->CompanyEmail) && !empty($request->CompanyPhone) && !empty($request->CompanyAddressLine1) && !empty($request->CompanyCity) && !empty($request->CompanyState) && !empty($request->CompanyCountry) && !empty($request->CompanyPostalCode) && !empty($request->FirstName) && !empty($request->LastName) && !empty($request->UserEmail) && !empty($request->UserJobtitle) && !empty($request->UserPhone)){
           
            $user->FirstName = $request->FirstName;
            $user->LastName =  $request->LastName;
            $user->UserEmail = $request->UserEmail;
            $user->UserJobtitle = $request->UserJobtitle;
            $user->UserPhone = $request->UserPhone;
            $user->CreatedBy = Auth::user()->id;
    
            // if(!isset($request->update)){
            // $user->password = Hash::make($request->UserPassword);
            // }else{
            // $request->UserPassword !=="";
            // }

            if(property_exists($request, 'UserPassword') && $request->UserPassword !== null && $request->UserPassword !='' && $request->UserPassword !=null)
            {
            $user->password = Hash::make($request->UserPassword);
            }

            $user->UserType = 4;
    
            $user->save();
    
    
            if($request->hasFile('CompanyLogo')){
    
                $this->validate( $request,[
                    'CompanyLogo' => 'mimes:jpeg,png,jpg |max:1096',
                ]);
    
    
                $file = $request->file('CompanyLogo');
                $name = time().$file->getClientOriginalName();
                $filepath = public_path('CompanyLogo/');
    
                $path = $file;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $filedata = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);
    
                $file->move($filepath,$name);
    
                if(property_exists($request, 'update') && $request->update !== null){
                    File::delete($filepath.$data->ArcCompanylogo);
                }
    
                $data->ArcCompanylogo = $name;
                $data->ArcComplogoBase64 = $base64;
                $user->UserImage = $name;
            }
    
            $pass = random_int(10000,100000);
    
            $data->ArcCompanyName = $request->CompanyName;
            $data->ArcCompanyWebsite = $request->CompanyWebsite;
            $data->ArcCompanyEmail = $request->CompanyEmail;
            $data->ArcCompanyPhone = $request->CompanyPhone;
            $data->ArcCompanyAddressLine1 = $request->CompanyAddressLine1	;
            $data->ArcCompanyCity = $request->CompanyCity;
            $data->ArcCompanyState = $request->CompanyState;
            $data->ArcCompanyCountry = $request->CompanyCountry;
            $data->ArcCompanyPostalCode = $request->CompanyPostalCode;
            $data->ArcMoreInfo = $request->CompanyMoreInfo;
    
    
    
    
            $data->ArcCompanyAddressLine1 = $request->CompanyAddressLine1;
    
            $data->UserId = $user->id;
            $data->Lat = $request->Lat;
            $data->Long = $request->Long;
            $data->save();
    
    
            // sending_mail_credential($user->UserEmail, $request->UserPassword);
    
    
    
            $request->session()->flash($flash, 'data');
            return redirect()->route('Architect/list');
        }else{
            $request->session()->flash('error','Please fill required field!');
            return redirect()->back();
        }

        

}

public function list()
{
    if (Auth::user()->UserType=='1') {
        $data = Architect::join('users','users.id','architects.UserId')->select('users.FirstName','users.UserPhone','architects.*')->where('users.UserType','4')->OrderBy('users.id','desc')->get();
        return view('Architects.Architectlist',['data' => $data]);
    } elseif (Auth::user()->UserType=='4') {
        return redirect()->route('Architect/profile');
    } else {
        return redirect('Architects/details/'.Auth::user()->ArchitectId);
    }
}

public function details($id)
{
    if (Auth::user()->UserType=='1') {
        if(!empty($id)){
            $data = Architect::join('users','users.id','architects.UserId')
            ->select('users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle' ,'users.UserPhone','architects.*')
            ->where('architects.id',$id)->first();
            return view('Architects.ArchitectDetails',['data' => $data]);
        } else {
            return redirect()->route('Architect/list');
        }
    } elseif (Auth::user()->UserType=='4') {
        $id = Auth::user()->id;
        //    Session::get('id');
        //    die();
        $data = Architect::join('users','users.id','architects.UserId')
        ->select('users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle' ,'users.UserPhone','architects.*')
        ->where('architects.UserId',$id)->first();
        $data['auth']=Auth::user()->UserType;
        return view('Architects.ArchitectDetails',['data' => $data]);
    }

    return null;
}

public function profile()
{
   if(Auth::user()->UserType=='4'){

        $data = Architect::join('users','users.id','architects.UserId')->select('users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle','users.UserPhone','architects.*')->where('architects.UserId',Auth::user()->id)->first();
        $data['auth']=Auth::user()->UserType;

        return view('Architects.ArchitectDetails',['data' => $data]);
}

   return null;
}

public function edit($id)
{
    if(Auth::user()->UserType=='1' || Auth::user()->UserType=='4'){
        if(isset($id)){
            $editdata = Architect::join('users','users.id','architects.UserId')->select('users.FirstName','users.LastName', 'users.UserEmail','users.UserPhone','users.UserJobtitle','architects.*')->where('architects.id',$id)->first();
            if(!empty($editdata) && (array)$editdata !== [])
            {
                return view('Architects.AddArchitect',['editdata' => $editdata]);
            } else {
                return redirect()->route('Architect/list');
            }
        } else {
            return redirect()->route('Architect/list');
        }
    }

    return null;
}

public function deleteArchitect(Request $request)
    {

        $ArchitectId = $request->ArchitectId;
        $data = Architect::where('id' ,$ArchitectId)->first();
        $delete_user = User::where('id',$data->UserId)->first();
        $data->delete();
        $delete_user->delete();
        return redirect()->back()->with('success', 'The Architect deleted successfully!');

    }
    }


