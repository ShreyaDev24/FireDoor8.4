<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FirstName','LastName','UserEmail','UserImage','UserId' ,'password','UserType','UserMoreInfo','UserPhone','UserJobTitle',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //To get users of comapany
    public function Company(){
        return $this->belongsTo(Company::class,'UserId','id');
    }
    public static function UserList($UserId){
        $user=User::where('id',$UserId)->get();
        return $user;
    }
    public static function GetCompanyAdmin($UserId,$UserType){
        $user=User::where('id',$UserId)->where('UserType',$UserType)->first();
        return $user;
    }
}
