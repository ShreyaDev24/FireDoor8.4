<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Company;
use URL;
// use App\Traits\ImageUploadTrait;
//use ImageUploadTrait;
class AddUserController extends Controller
{
    //use ImageUploadTrait;

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
     * Show the application adduser or create user.
     *
     * @return \Illuminate\Http\Response
     */

   	public function index(Request $data,$CompanyId,$UserId=null)
    {
        $User=[];
        if(isset($UserId)){
            $UserId      =Crypt::decrypt($UserId);
            $User        =User::findorfail($UserId);
        }
        
        $CompanyId=Crypt::decrypt($CompanyId);
        $Company=Company::findorfail($CompanyId);
    	if($data->has('UserEmail')){
            if($data->has('UserPhoto')){
                $this->validate($data, [
                    'UserPhoto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $image = $data->file('UserPhoto');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('storage/usersphoto');
                $image->move($destinationPath, $name);
                //dd($name);
                $data['UserPhotoName']=$name;

            }elseif(!($data['UserPhotoName']) && isset($UserId)){
                $data['UserPhotoName']=$User['UserImage'];

            }else{
                $data['UserPhotoName']=null;
            }
            
    		$data= array_merge_recursive($data->all(),
                                        [
                                        'CompanyId'    =>$CompanyId,
                                        'UserId'       =>$UserId,
    					                   ]
                                        );

    		/* validating form data by calling validator method */
            $this->validator($data,$UserId);

            /* creating or updating data after validation*/
            if ($UserId) {
                $data['password']=$User['password'];
                $data['UserType']=$User['UserType'];
                $this->UpdateOrCreateUser($data);

                $UserIdArgument =Crypt::encrypt($UserId);
                $url=URL::route('userprofile.show',$UserIdArgument);

                return redirect($url)->with('success' , 'User updated successfully');

            }else{
                $data['password']=bcrypt($data['password']);
                $data['UserType']=3;
                $this->UpdateOrCreateUser($data);

                $CompanyIdArgument =Crypt::encrypt($CompanyId);
                $url=URL::route('userlist.show',$CompanyIdArgument);

                return redirect($url)->with('success' , 'User updated successfully');
            }
        }
        
        if(isset($UserId)){
     	  return view('adduser',['company'=>$Company],['user'=>$User]);
        }
        
        return view('adduser',['company'=>$Company]);
 	}
       
 	/**
     * Get a validator for an incoming add user data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data,string $UserId)
    {
        return Validator::make($data, [
            'FirstName'     => 'required|string|max:255',
            'LastName'      => 'required|string|max:255',
            'JobTitle'      => 'required|string|max:255',
            'UserPhone'     => 'nullable|string|max:255',
            'UserEmail'     => 'required|string|email|max:255|unique:users,UserEmail,'.$UserId.'id',
            'password'      => 'sometimes|required|string|min:6|confirmed',
            'MoreInfo'      => 'nullable|string|max:500',
        ])->validate();
    }
    
     /**
     * Create a new user instance.
     *
     * @param  array  $data
     * @return \App\Models\Users
     */
    protected function UpdateOrCreateUser(array $data)
    {
        $matchThese = ['id'=>$data['UserId']];
        return User::updateOrCreate($matchThese,[
            'FirstName'    => $data['FirstName'],
            'LastName'     => $data['LastName'],
            'UserImage'    => $data['UserPhotoName'],
            'UserEmail'    => $data['UserEmail'],
            'UserJobTitle' => $data['JobTitle'],
            'UserPhone'    => $data['UserPhone'],
            'UserMoreInfo' => $data['MoreInfo'],
            'UserType'     => $data['UserType'],
            'CompanyId'    => $data['CompanyId'],
            'password'     => $data['password'],
        ]);
    }
}
