<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFfooter;
use App\Models\SettingPDFDocument;

class PdfsettingController extends Controller
{
    public function settingpdf1()
    {
        if(Auth::user()->UserType== 2){
            $ids = CompanyUsers(true);
        }else{
            $ids = [Auth::user()->id];
        }

        $pdf1 = SettingPDF1::wherein('UserId',$ids)->first();
        $pdf2 = SettingPDF2::wherein('UserId',$ids)->first();
        $pdf_footer = SettingPDFfooter::wherein('UserId',$ids)->first();
        $pdf_document = SettingPDFDocument::wherein('UserId',$ids)->first();
        return view('Setting.settingpdf1',compact('pdf1','pdf2','pdf_footer','pdf_document'));
    }
    public function submitpdf1(Request $request)
    {
        $id = Auth::user()->id;

        $valid = $request->validate([
            'editor1' => 'required'
        ],
        [
           'editor1.required' => 'The PDF Formate One field is required.',
        ]);
        if(Auth::user()->UserType== 2){
            $users = CompanyUsers(true);
            SettingPDF1::wherein('UserId', $users)->delete();
        }else{
            $users = [$id];
        }
        foreach ($users as $key => $usr) {
            $update_val = $request->updval;
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = SettingPDF1::find($update_val);
            } else {
                $a = new SettingPDF1;
            }
            $a->UserId = $usr;
            $a->msg = $request->editor1;
            $a->created_at = date('Y-m-d H:i:s');
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }

        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'PDF Format One update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'PDF Format One added successfully!');
        }
    }
    public function submitpdf2(Request $request)
    {
        $id = Auth::user()->id;

        $valid = $request->validate([
            'editor2' => 'required'
        ],
        [
           'editor2.required' => 'The PDF Formate Two field is required.',
        ]);
        if(Auth::user()->UserType== 2){
            $users = CompanyUsers(true);
            SettingPDF2::wherein('UserId', $users)->delete();
        }else{
            $users = [$id];
        }
        foreach ($users as $key => $usr) {
            $update_val = $request->updval2;
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = SettingPDF2::find($update_val);
            } else {
                $a = new SettingPDF2;
            }
            $a->UserId = $usr;
            $a->msg = $request->editor2;
            $a->created_at = date('Y-m-d H:i:s');
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'PDF Format Two update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'PDF Format Two added successfully!');
        }
    }

    public function submitpdf3(Request $request)
    {
        $id = Auth::user()->id;

        $valid = $request->validate([
            'editor3' => 'required'
        ],
        [
           'editor3.required' => 'The PDF footer field is required.',
        ]);
        if(Auth::user()->UserType== 2){
            $users = CompanyUsers(true);
            SettingPDFfooter::wherein('UserId', $users)->delete();
        }else{
            $users = [$id];
        }
        foreach ($users as $key => $usr) {
            $update_val = $request->updval3;
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = SettingPDFfooter::find($update_val);
            } else {
                $a = new SettingPDFfooter;
            }
            $a->UserId = $usr;
            $a->msg = $request->editor3;
            $a->created_at = date('Y-m-d H:i:s');
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'PDF footer update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'PDF footer added successfully!');
        }
    }
    public function submitpdf4(Request $request)
    {
        $id = Auth::user()->id;

        $valid = $request->validate([
            'editor4' => 'required'
        ],
        [
           'editor4.required' => 'The PDF document field is required.',
        ]);
        if(Auth::user()->UserType== 2){
            $users = CompanyUsers(true);
            SettingPDFDocument::wherein('UserId', $users)->delete();
        }else{
            $users = [$id];
        }
        foreach ($users as $key => $usr) {
            $update_val = $request->updval4;
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = SettingPDFDocument::find($update_val);
            } else {
                $a = new SettingPDFDocument;
            }
            $a->UserId = $usr;
            $a->msg = $request->editor4;
            $a->created_at = date('Y-m-d H:i:s');
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'PDF document update successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'PDF document added successfully!');
        }
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;

            $request->file('upload')->move(public_path('images/pdf/'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/pdf/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
