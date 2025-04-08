<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\User;
use URL;
use Mail;
use Hash;
use Session;

class SurveyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {

        if (Auth::user()->UserType == '2') {
            $userIds = getMyCreatedAdmins();
            $data = User::where('UserType', 6)->wherein('CreatedBy', $userIds)->orderBy('id', 'desc')->get();

            return view('Survey.SurveyList', ['data' => $data]);
        }
        
        // }else if(Auth::user()->UserType=='1'){
        //     $data = User::where('UserType',3)->orderBy('id','desc')->get();
        //     return view('Users.UserList',compact('data'));
        // }
        // else{
        //     return redirect()->route('users/details');
        // }
        return null;
    }

    public function add()
    {
        if (Auth::user()->UserType == '2') {
            return view('Survey.AddSurvey');
        }

        return null;
    }

    public function details($id)
    {
        if (Auth::user()->UserType == '2') {
            if (isset($id)) {
                $data = User::where('id', $id)->first();
                return view('Survey.SurveyDetails', ['data' => $data]);
            } else {
                return redirect()->route('survey/details');
            }
        } else {
            return redirect()->route('survey/details');
        }
    }
    
    // public function profile()
    // {
    //     if(Auth::user()->UserType=='3'){
    //       $id = Auth::user()->id;
    //     if(isset($id)){
    //         $data = User::where('id',$id)->first();
    //         // echo"212";
    //         // die();
    //        return view('Users.UserDetails',compact('data'));

    //     }
    //     else{
    //         return redirect()->route('user/profile');

    //     }
    // }else{
    //     return redirect()->route('user/profile');
    // }
    // }



    public function store(request $request)
    {
        if (property_exists($request, 'update') && $request->update !== null) {
            $user = User::where('id', $request->update)->first();
            $flash = "updated";
        } else {
            $user = new User();
            $flash = "added";
            Validator::extend('without_spaces', function($attr, $value){
                return preg_match('/^\S*$/u', $value);
            });
            $this->validate(
                $request,
                [
                    // 'FirstName' => "required|email|unique:users",
                    // 'LastName' => "required|email|unique:users",
                    'FirstName' => 'required',
                    'LastName' => 'required',
                    'UserJobtitle' => 'required',
                    'UserEmail' => "required|email|unique:users",
                    'UserPhone'=> "required"
                ]
            );
        }

        if(!empty($request->FirstName) && !empty($request->LastName) &&  !empty($request->UserEmail) &&  !empty($request->UserPhone) && !empty($request->UserJobtitle)){

        if ($request->hasFile('UserImage')) {
            $file = $request->file('UserImage');
            $name = time() . $file->getClientOriginalName();
            $filepath = public_path('UserImage');
            $file->move($filepath, $name);
            $user->UserImage = $name;
        }
        else{
            $user->UserImage = $request->oldImage;
        }

        $user->FirstName = $request->FirstName;
        $user->LastName =  $request->LastName;
        $user->UserEmail = $request->UserEmail;
        $user->UserPhone = $request->UserPhone;
        $user->UserJobtitle = $request->UserJobtitle;
        $user->CreatedBy = Auth::user()->id;
        $user->UserType = 6;

        $user->CreatedBy = Auth::user()->id;

        if (!property_exists($request, 'update') || $request->update === null) {
            $password = random_int(100000,1000000);
            $user->password = Hash::make($password);
            $emailTo = $request->UserEmail;
            $subject = 'Login Password';
            $emailFrom = 'noreply@jfds.co.uk';
            $usermname = $request->FirstName.' '.$request->LastName;
            $data_set = ['usermname'=>$usermname,'pass'=>$password];

            ini_set('display_errors', 1);
            try{
                Mail::send(['html' => 'Mail.Password'], $data_set, function($message) use(&$emailTo, &$subject, &$emailFrom): void {

                    $message->to($emailTo, $emailTo)->subject($subject);
                    if($emailFrom !== ''){
                        $message->from($emailFrom, $emailFrom);
                    }

                });

            } catch (Exception $e) {
                    echo $e->getMessage();
            }
        }
        
        $user->save();
        // sending_mail_credential($user->UserEmail, $request->password);
        $request->session()->flash($flash, 'data');
        return redirect()->route('survey/list');
    }else{
            $request->session()->flash('error','Please fill required field!');
            return redirect()->back();
    }
    }


    public function edit($id)
    {
        if (Auth::user()->UserType == '2') {
            if (isset($id)) {
                $editdata = User::where('id', $id)->first();
                return view('Survey.AddSurvey', ['editdata' => $editdata]);
            } else {
                return redirect()->route('survey/list');
            }
        } else {
            return redirect()->route('survey/list');
        }
    }

    public function delete(request $request){

        if (Auth::user()->UserType == '2') {
            if (property_exists($request, 'id') && $request->id !== null) {
                User::where('id', $request->id)->delete();
                return json_encode(["status" => "ok", "msg" => "Survey User Deleted!"]);

            } else {
                return redirect()->route('survey/list');
            }
        } else {
            return redirect()->route('survey/list');
        }
    }

    public function statusChange(request $request){

        if (Auth::user()->UserType == '2') {
            if (property_exists($request, 'id') && $request->id !== null) {
                if($request->status == 1){
                    User::where('id', $request->id)->update(['status' => 0]);
                    return json_encode(["status" => "ok", "msg" => "Survey User Deleted!"]);
                }
                else{
                    User::where('id', $request->id)->update(['status' => 1]);
                    return json_encode(["status" => "ok", "msg" => "Survey User Deleted!"]);
                }
                
                return redirect()->route('survey/list');
            } else {
                return redirect()->route('survey/list');
            }
        }

        return null;
    }
}
