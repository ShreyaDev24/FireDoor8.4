<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\user;

class UserListController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($UserId)
    {
        $UserId =Crypt::decrypt($UserId);
        $user=User::UserList($UserId);
        return view('userlist',['userlist'=>$user],['companyid'=>$UserId]);
    }
}
