<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mail;


class PhpMailerController extends Controller
{
    public function index(){
        $data = ['name','kunal soni'];
        Mail::send('TestMail',$data,function($m){
            $m->to('kunal1071996@gmail.com','kunal soni');
            $m->from('kunal1071996@gmail.com','kunal soni');
        });
        return 'success';
    }
}
