<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use Hash;
class ResetPasswordController extends Controller
{

    public function forgotpassword(request $request){
        return view('auth.forgotpassword');
    }

    public function otp(request $request){
        return view('auth.otp');
    }

    public function resetpassword(request $request){
        return view('auth.resetpassword');
    }

    public function resetstore(request $request){

    }

    public function __construct(){
        $this->middleware('auth');
    }

    public function index($id=""){

        if(!empty($id)){
            if(Auth::user()->UserType==2){
            $data = User::where([['id',"=", $id], ['id',"=", Auth::user()->id]])->first();
            }
            elseif(Auth::user()->UserType==4){
            $data = User::where([['id',"=", $id], ['ArchitectId',"=", Auth::user()->ArchitectId]])->first();
            }

            if($data){

                return view('ChangePassword');

            }else{
                return redirect("ChargePassword");
            }

        }else{
            return view('ChangePassword');
        }

    }

    public function change_password(request $request){

        if(Auth::user()->UserType == "2" && !empty($request->id)){

            $id = $request->id;

            $data = User::where([['id',"=", $id],['id',"=", Auth::user()->id]])->first();

            if($data){

                $data->password = Hash::make($request->password);
                $data->save();

//                $flash = 'updated';
//                $request->session()->flash($flash, 'data');

                if($data->UserType =='3'){

                    return redirect('user/details/'.$id);

                }else{
                    return redirect()->route('company/profile');
                }

            }else{
                return redirect("/");
            }


        }else{

            $id = Auth::user()->id;

            $data = User::where('id',$id)->first();
            $data->password = Hash::make($request->password);
            $data->save();
            $flash = 'updated';
            $request->session()->flash($flash, 'data');

            if(Auth::user()->UserType=='2'){
                $company_id = get_company_id(Auth::user()->id)->id;
                Session::put('c_id',$company_id);
                return redirect()->route('company/profile');
            }elseif(Auth::user()->UserType =='3'){
                return redirect('user/details/'.$id);
            }
            elseif(Auth::user()->UserType=='4'){
                return redirect()->route('Architect/profile');
            }
            elseif(Auth::user()->UserType=='5'){
                return redirect("/");
            }
            else{
                return redirect()->route('admin/profile');
            }


        }


    }

    public function admin_profile(request $request){
    	if(Auth::user()->UserType=='1'){
    	return view('Admin.Profile');
    	}

        return null;

    	}
    }




