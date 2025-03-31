<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\User;
use URL;
use Hash;
use Session;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {

        if(Auth::user()->UserType=='2' ){
        // $data = User::whereIn('UserType',[2,3])->where('CreatedBy',Auth::user()->id)->orderBy('id','desc')->get();
        $myCreatedUser = myCreatedUser();

        $data = User::whereIn('UserType',[2,3])->whereIn('CreatedBy', $myCreatedUser)->orderBy('id','desc')->get();

        return view('Users.UserList',compact('data'));
        }else if(Auth::user()->UserType=='1'){
            $data = User::join('companies','companies.UserId','users.CreatedBy')->where('users.UserType',3)->orderBy('users.id','desc')->select('users.*','companies.CompanyName','companies.id as comId')->get();
            return view('Users.UserList',compact('data'));
        }
        else{
            return redirect()->route('users/details');
        }
    }

    public function add()
    {
        if(Auth::user()->UserType=='2'){
            return view('Users.AddUser');
        }
    }

    public function details($id)
    {
        markAsRead($id, 'user');
        if(Auth::user()->UserType=='1' || Auth::user()->UserType=='2' || Auth::user()->UserType=='3'){
        if(isset($id)){
            $data = User::where('id',$id)->first();
            // echo"212";
            // die();
           return view('Users.UserDetails',compact('data'));

        }
        else{
            return redirect()->route('user/details');

        }
    }else{
        return redirect()->route('user/details');
    }
    }
    public function profile()
    {
        if(Auth::user()->UserType=='3'){
          $id = Auth::user()->id;
        if(isset($id)){
            $data = User::where('id',$id)->first();
            // echo"212";
            // die();
           return view('Users.UserDetails',compact('data'));

        }
        else{
            return redirect()->route('user/profile');

        }
    }else{
        return redirect()->route('user/profile');
    }
    }



    public function store(request $request)
    {
        if(isset($request->update)){
        $user = User::where('id',$request->update)->first();
        $flash = "updated";
        }
        else{
        $user = new User();
        $flash = "added";
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
         $this->validate(
        $request,
        [
            // 'FirstName' => 'required|without_spaces',
            // 'LastName' => 'required|without_spaces',
            // 'UserJobtitle' => 'required|without_spaces',
            'UserEmail'=> "required|email|unique:users",
            'UserPhone'=> "required"
        ]
        );
        }

        if(!empty($request->FirstName) && !empty($request->LastName) &&  !empty($request->UserEmail) &&  !empty($request->UserPhone) && !empty($request->UserJobtitle)){

        if($request->hasFile('UserImage')){
            $file = $request->file('UserImage');
            $name = time().$file->getClientOriginalName();
            $filepath = public_path('UserImage');
            $file->move($filepath,$name);
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
        $user->parent_id = Auth::user()->parent_id ? Auth::user()->parent_id : Auth::user()->CreatedBy;

        $user->UserType = 3;
        if(!isset($request->update)){
        $user->password =  Hash::make($request->password);
        }
        $user->CreatedBy = Auth::user()->id;
        $user->parent_id = Auth::user()->parent_id ? Auth::user()->parent_id : Auth::user()->CreatedBy;

        $user->save();
        //sending_mail_credential($user->UserEmail, $request->password);
        $request->session()->flash($flash, 'data');

        if(Auth::user()->UserType=='3'){
            return redirect('user/profile');
       }else{
            return redirect()->route('user/list');
       }
    }else{
            $request->session()->flash('error','Please fill required field!');
            return redirect()->back();

        }
    }


    public function edit($id)
    {
        if(Auth::user()->UserType=='2' || Auth::user()->UserType=='3'){
            if(isset($id)){
                $editdata = User::where('id',$id)->first();
                return view('Users.AddUser',compact('editdata'));
                }else{
                return redirect()->route('user/list');
             }

       }else{
        return redirect()->route('user/list');
       }

    }

    public function delete(request $request){
//
        if (Auth::user()->UserType == '2') {
            if (isset($request->id)) {
                User::where('id', $request->id)->delete();
                return json_encode(array("status" => "ok", "msg" => "User Deleted!"));

            } else {
                return redirect()->route('user/list');
            }
        } else {
            return redirect()->route('user/list');
        }
    }

}
