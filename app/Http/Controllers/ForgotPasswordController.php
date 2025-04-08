<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use Hash;
use Mail;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ForgotPasswordController extends Controller
{

    public function forgotpassword(request $request){
        return view('auth.forgotpassword');
    }

    public function otpstore(request $request){
        $check_email = User::where('UserEmail',$request->UserEmail)->count();
        if($check_email=='1'){
            // $randomcode = rand(000000,999999);
            // Session::put('otp',$randomcode);
            // $data = ['OTP'=>$randomcode,'email'=>$request->Email];

            $token = random_int(000000,999999);

            DB::table('password_resets')->insert([
                'email' => $request->UserEmail,
                'token' => $token,
                'created_at' => Carbon::now()
              ]);
            $emailFrom = 'noreply@jfds.co.uk';
            ini_set('display_errors', 1);
            Mail::send('Mail.forgetPassword', ['token' => $token], function($message) use($request,$emailFrom): void{
                $message->to($request->UserEmail);
                if($emailFrom !== ''){
                    $message->from($emailFrom, $emailFrom);
                }
                
                $message->subject('Reset Password');
            });

            // Mail::send('Mail.OTP',$data,function($message) use ($data){
            //     $message->to('pradip@resiliencesoft.com','pradip parker');
            //     $message->from('firedoor@workdemo.online','firedoor');
            // });
            // return redirect()->route('ForgotPassword');
            return back()->with('message', 'We have e-mailed your password reset link!');
        }

        else{
            $request->session()->flash('error','Email does not exists');
            return redirect()->route('ForgotPassword');
        }
    }

    public function otp(request $request){
        return view('auth.otp');
    }

    public function resetpassword($token){
        return view('auth.resetpassword', ['token' => $token]);
    }

    public function resetstore(request $request){
        $request->validate([
            // 'UserEmail' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        // print_r($request);die;
        // if (!$validator->fails()){

            $updatePassword = DB::table('password_resets')
                                ->where([
                                  'email' => $request->email,
                                  'token' => $request->token
                                ])
                                ->first();

            if(!$updatePassword){
                // $request->session()->flash('message','Email does not exists');
                return redirect()->back()->with('message', 'Invalid email or token!');
            }

            $user = User::where('UserEmail', $request->email)
                        ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email'=> $request->email])->delete();

            return redirect('/login')->with('message', 'Your password has been changed!');
        // }else{
        //     return $validator->errors();
        // }
    }

}
