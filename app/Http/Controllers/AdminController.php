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
use DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application additem or create item.
     *
     *
     */
    public function add()
    {
        return view('Admins.add');
    }

    public function store(request $request)
    {
        if (property_exists($request, 'update') && $request->update !== null) {
            $user = User::where('id', $request->update)->first();
            $flash = "updated";
        } else {
            $user = new User();
            $flash = "added";
            Validator::extend('without_spaces', fn($attr, $value): int|false => preg_match('/^\S*$/u', (string) $value));
            $this->validate(
                $request,
                [
                    // 'FirstName' => "required|email|unique:users",
                    // 'LastName' => "required|email|unique:users",
                    'FirstName' => 'required|without_spaces',
                    'LastName' => 'required|without_spaces',
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
                $filepath = public_path('CompanyLogo');
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
            $user->UserType = 2;
            $user->parent_id = Auth::user()->parent_id ?: Auth::user()->CreatedBy;


            // $user->CreatedBy = Auth::user()->id;
            if (!property_exists($request, 'update') || $request->update === null) {
                if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
                    $password = '123456';
                    $user->password = Hash::make($password);
                }else{
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
            }
            
            $user->save();
            // sending_mail_credential($user->UserEmail, $request->password);
            if (!property_exists($request, 'update') || $request->update === null) {
                $comp = get_company_id(Auth::user()->id);
                if($comp){
                    $val = $comp->id;
                }else{
                    $val = DB::table('user_data')->where(['type'=>'company', 'user_id'=>Auth::user()->id])->first()->value;
                }
                
                DB::table('user_data')->insert([
                    'user_id'=>$user->id,
                    'type' => 'company',
                    'value'=> $val
                ]);

                $parentId = DB::table('user_data')->where(['type'=>'parent', 'user_id'=>Auth::user()->id])->value('value');
                if(!$parentId){
                    DB::table('user_data')->insert([
                        'user_id'=>$user->id,
                        'type' => 'parent',
                        'value'=> auth()->user()->id
                    ]);
                }else{
                    DB::table('user_data')->insert([
                        'user_id'=>$user->id,
                        'type' => 'parent',
                        'value'=> $parentId
                    ]);
                }
            }
            
            $request->session()->flash($flash, 'data');
            return redirect()->route('user/list');
        }else{
                $request->session()->flash('error','Please fill required field!');
                return redirect()->back();
        }
    }

    public function list()
    {

        if (Auth::user()->UserType == '2') {
            $data = User::where('UserType', 2)->where('CreatedBy', Auth::user()->id)->orderBy('id', 'desc')->get();

            return view('Admins.list', ['data' => $data]);
        }

        return null;
    }

    public function details($id)
    {
        if (Auth::user()->UserType == '2') {
            if (isset($id)) {
                $data = User::where('id', $id)->first();
                return view('Admins.details', ['data' => $data]);
            } else {
                return redirect()->route('user/list');
            }
        } else {
            return redirect()->route('user/list');
        }
    }

    public function edit($id)
    {
        if (Auth::user()->UserType == '2') {
            if (isset($id)) {
                $editdata = User::where('id', $id)->first();
                return view('Admins.add', ['editdata' => $editdata]);
            } else {
                return redirect()->route('user/list');
            }
        } else {
            return redirect()->route('user/list');
        }
    }

    public function delete(request $request){

        if (Auth::user()->UserType == '2') {
            if (property_exists($request, 'id') && $request->id !== null) {
                User::where('id', $request->id)->delete();
                return json_encode(["status" => "ok", "msg" => "Admin Deleted!"]);

            } else {
                return redirect()->route('user/list');
            }
        } else {
            return redirect()->route('user/list');
        }
    }
}
