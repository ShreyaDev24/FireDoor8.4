<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ArchitectureQuatationFile;
use App\Models\RuleMaster;
use App\Models\ItemField;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\Option;
use URL;
use DB;
class ArchitectureController extends Controller
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
    public function index()
    {

        return view('Architecture.CreateFileType');


    }
    
    public function storeFileName(Request $request){

        if($request->id=="0"){
            // echo"321321";
            // die();
            $uploadedfile = $request->file;
             $extention = $uploadedfile->getClientOriginalExtension();


            $valid=[
                'csv','xls','xlsx' // add your extensions here.
            ];
            if(in_array($extention,$valid) ){

                $uploadedfileName = time().'.'.$uploadedfile->getClientOriginalName();
                // $uploadedfile->move(public_path('assets/quatationfiles'),$uploadedfileName);

                $uploadedfile->move(public_path('assets/quatationfiles/'), $uploadedfileName);

                $uploadedfilepath = 'public/assets/quatationfiles/'.$uploadedfileName;
                // die();
                $createFileName = new ArchitectureQuatationFile();
                $createFileName->filename = $request->filename ;
                $createFileName->filepath = $uploadedfilepath ;
                $createFileName->generated_id = random_int(0, mt_getrandmax());
                // $createFileName->created_at = time();
                // $createFileName->updated_at = time();
                $createFileName->status = 0;
                $createFileName->save();
                 $fileId =  $createFileName->id;

            // $id = DB::table('architecture_quatation_file')->insertGetId(
            //     [ 'filename' => $request->filename,
            //       'generated_id' => rand(),
            //       'status' => 0,
            //     //   'created_at' => time(),
            //     //   'updated_at' => time(),
            // ]);


            return back()
            ->with('success','File has been uploaded.')
            ->with('file', $uploadedfileName);

            } else{
                return back()
                ->with('error','File should be csv, xls, xlsx format');
                }



        }else{

            $createFileName = ArchitectureQuatationFile::find($request->id);
            $createFileName->filename = $request->filename ;
            $createFileName->status = 0 ;
            $createFileName->updated_at = time();
            $createFileName->save();
            $fileId =  $createFileName->id;

            // return redirect()->route('choose-add-data-option/', $fileId);
            return redirect('file/choose-add-data-option/'.$fileId);







    }
}



    public function chooseAddDataOption($id)
    {
        $isFileCreated = ArchitectureQuatationFile::find($id);


        if(!empty($isFileCreated)){

            return view('Architecture.ChooseOption',['id'=>$id]);

        }else{
            $createdFile = ArchitectureQuatationFile::all();
            return view('Architecture.ImportedFilelist',['files'=>$createdFile]);
        }

    }

    public function QuationFileList()
    {
        $createdFile = ArchitectureQuatationFile::orderBy('id', 'DESC')->get();
        return view('Architecture.ImportedFilelist',['files'=>$createdFile]);
    }


    public function FileImport($id)
    {
        // $createdFile = ArchitectureQuatationFile::all();
        return view('Architecture.ImportFile');
    }


public function delete($id){


        $res=ArchitectureQuatationFile::find($id);
        // print_r(  $res['filepath']);
  if ($res){

    unlink($res['filepath']);
    $res->delete();
    return back()
    ->with('success','File has been deleted successfully.');

  }else{
    return back()
    ->with('error','There is some technical problem please try again later');

  }
}

}
