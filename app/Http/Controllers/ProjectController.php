<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;
use URL;
use Hash;
use Session;
use View;
use App\Imports\DoorScheduleImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Company;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectBuildingDetails;
use App\Models\DoorSchedule;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\QuotationContactInformation;
use App\Models\ProjectFiles;
use App\Models\ProjectFilesDS;
use App\Models\Option;
use App\Models\SettingCurrency;


class ProjectController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {

            $loginUser = auth()->user();
            if (!in_array($loginUser->UserType, ["2", "3", "1", "4"])) {
                return redirect("/");
            }
            
            return $next($request);
        });
    }



    public function index($id = "")
    {

        $projects = '';
        $ProjectFiles = '';


        if (Auth::user()->UserType == 2) {
            $myAdminGroup = myCreatedUser();
            $useTbl = $myAdminGroup;

            $loginUserId = Auth::user()->id;
            $loginUserType = Auth::user()->UserType;
        } else {

            $loginUserId = Auth::user()->id;
            $loginUserType = Auth::user()->UserType;
        }

        switch ($loginUserType) {
            case '3':
                $created_by_my_cmpny_admin_user = User::where('CreatedBy', Auth::user()->CreatedBy)->where('UserType', 3)->pluck('id')->toArray();
                $created_by_my_cmpny_admin_user[] = intval(Auth::user()->CreatedBy);
                $companykacustomer = Customer::join('users', 'users.id', 'customers.UserId')
                    ->whereIn('users.CreatedBy', $created_by_my_cmpny_admin_user)
                    ->orderBy('customers.id', 'desc')->get();
                break;
            case '2':
                $companykacustomer = Customer::join('users', 'users.id', 'customers.UserId')
                    ->whereIn('users.CreatedBy', $useTbl)
                    ->orderBy('customers.id', 'desc')->get();
                break;
            default:
                $companykacustomer = Customer::join('users', 'users.id', 'customers.UserId')->where(['users.CreatedBy' => $loginUserId])->orderBy('customers.id', 'desc')->get();
                break;
        }

        $UserIds = CompanyUsers();
        $OptionsData = Option::where(['configurableitems' => 1, 'is_deleted' => 0])->wherein('editBy', $UserIds)->get();

        $currency = SettingCurrency::where('UserId', Auth::user()->id)->first();

        if (!empty($id)) {

            switch ($loginUserType) {
                case 1:
                    $projects = Project::where('GeneratedKey', $id)->first();
                    break;
                case 2:
                    $login_company_id = get_company_id(Auth::user()->id)->id;
                    $projects = Project::where([
                        ['GeneratedKey', '=', $id],
                        ['CompanyId', '=', $login_company_id]
                    ])->first();
                    break;
                case 3:
                    $projects = Project::where([
                        ['GeneratedKey', '=', $id],
                        ['UserId', '=', $loginUserId]
                    ])->first();
                    break;
                default:
                    $projects = Project::where([
                        ['GeneratedKey', '=', $id],
                        ['UserId', '=', $loginUserId]
                    ])->first();
            }
            
            $projects = Project::where('GeneratedKey', $id)->first();
            $ProjectFiles = ProjectFiles::where('projectId', $projects->id)->get();
            $ProjectBuildingDetails = ProjectBuildingDetails::where('projectId', $projects->id)->get();

            if (!empty($projects) && (array)$projects !== []) {
                return view('Project.CreateProject', ['projects' => $projects, 'companykacustomer' => $companykacustomer, 'ProjectFiles' => $ProjectFiles, 'currency' => $currency, 'loginUserType' => $loginUserType, 'ProjectBuildingDetails' => $ProjectBuildingDetails, 'OptionsData' => $OptionsData]);
            } else {
                return redirect()->route('project/list');
            }
        } else {
            return view('Project.CreateProject', ['projects' => $projects, 'companykacustomer' => $companykacustomer, 'ProjectFiles' => $ProjectFiles, 'currency' => $currency, 'loginUserType' => $loginUserType, 'OptionsData' => $OptionsData]);
        }
    }


    public function store(request $request)
    {
        if (!empty($request->AddressLine1) && !empty($request->City) && !empty($request->Country) && !empty($request->PostalCode)) {

            $valid = $request->validate([
                'ProjectImage' => 'nullable|image|mimes:jpeg,png,jpg',
                'AddressLine1' => 'required|string|max:150',
            ]);
            $msg = '';
            $update_val = $request->updval;
            if (!is_null($update_val)) {
                $id = $request->updval;
                $loginUserId = Auth::user()->id;
                $loginUserType = Auth::user()->UserType;

                switch ($loginUserType) {
                    case 1:
                        $project = Project::where('GeneratedKey', $id)->first();
                        break;
                    case 2:
                        $login_company_id = get_company_id(Auth::user()->id)->id;
                        $project = Project::where([
                            ['GeneratedKey', '=', $id],
                            ['CompanyId', '=', $login_company_id]
                        ])->first();
                        break;
                    case 3:
                        $project = Project::where([
                            ['GeneratedKey', '=', $id],
                            ['UserId', '=', $loginUserId]
                        ])->first();
                        break;
                        //firedoor2_role_update
                    case 4:
                        $login_architect_id = get_architect_id(Auth::user()->id)->id;
                        $project = Project::where([
                            ['GeneratedKey', '=', $id],
                            ['ArchitectId', '=', $login_architect_id]
                        ])->first();
                        break;
                    default:
                        $project = Project::where([
                            ['GeneratedKey', '=', $id],
                            ['UserId', '=', $loginUserId]
                        ])->first();
                }
                
                ProjectBuildingDetails::where('projectId', $project->id)->delete();
            } else {
                $project = new Project();
                $genratedKey = $this->RandomString();
                $project->GeneratedKey = $genratedKey;
                $get_architect_id = get_architect_id(Auth::user()->id);
                $project->UserId = Auth::user()->id;
                // $project->UserId = Auth::user()->id;
                if (Auth::user()->UserType == '2') {
                    $get_company_id = get_company_id(Auth::user()->id);
                    $project->CompanyId = $get_company_id->id;
                    $project->ProjectStatus = 'Accepted';
                } elseif (Auth::user()->UserType == '4') {
                    $project->ProjectStatus = 'Created';
                    $project->ArchitectId = $get_architect_id->id;
                }
                
                $project->created_at = date('Y-m-d H:i:s');
            }
            
            if (!empty($project)) {
                $returnTenderDate = date('Y-m-d', strtotime($request->returnTenderDate));
                if ($request->hasFile('ProjectImage')) {
                    $file = $request->file('ProjectImage');
                    $path = $file;
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $filedata = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);
                    $project->projectImageBase64 = $base64;

                    $ImageName = rando() . $file->getClientOriginalName();
                    $filepath = public_path('uploads/Project/');
                    $ImageExtension = $file->getClientOriginalExtension();
                    $ext = pathinfo($filepath . $ImageName, PATHINFO_EXTENSION);
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                        $file->move($filepath, $ImageName);
                        File::delete($filepath . $project->ProjectImage);
                        $project->ProjectImage = $ImageName;
                    }
                }
                
                // if(Auth::user()->UserType=='4'){
                //     customerId ==null;
                // }
                // else{
                //     $project->customerId = $request->customerId;

                // }

                $project->customerId = $request->customerId;
                $project->BuildingType = $request->building_type;
                $project->ProjectName = $request->ProjectName;
                // $project->ProjectFiles = json_encode($projectFilesFieldsJSON);
                $project->AddressLine1 = $request->AddressLine1;
                $project->AddressLine2 = $request->AddressLine2;
                $project->AddressLine3 = $request->AddressLine3;
                $project->latitude = $request->Lat;
                $project->longitude = $request->Long;
                $project->City = $request->City;
                $project->Province = $request->Province;
                $project->Country = $request->Country;
                $project->PostalCode = $request->PostalCode;
                $project->MoreInformation = $request->MoreInformation;
                $project->returnTenderDate = $returnTenderDate;
                $project->projectCurrency = $request->projectCurrency;
                $project->editBy = Auth::user()->id;
                $project->created_by = Auth::user()->UserType;
                $project->Status = 1;
                // certification
                $project->ioNumberOne = $request->ioNumberOne;
                $project->ioNumberTwo = $request->ioNumberTwo;
                $project->ioNumberThree = $request->ioNumberThree;
                $project->doorPoOne = $request->doorPoOne;
                $project->doorPoTwo = $request->doorPoTwo;
                $project->doorPoThree = $request->doorPoThree;
                $project->framePoOne = $request->framePoOne;
                $project->framePoTwo = $request->framePoTwo;
                $project->framePoThree = $request->framePoThree;

                $project->glassPoOne = $request->glassPoOne;
                $project->glassPoTwo = $request->glassPoTwo;
                $project->glassPoThree = $request->glassPoThree;
                $project->ironmongeryPoOne = $request->ironmongeryPoOne;
                $project->ironmongeryPoTwo = $request->ironmongeryPoTwo;
                $project->ironmongeryPoThree = $request->ironmongeryPoThree;
                $project->intumescentPoOne = $request->intumescentPoOne;
                $project->intumescentPoTwo = $request->intumescentPoTwo;
                $project->intumescentPoThree = $request->intumescentPoThree;
                // end certification
                // MOVE FROM QUOTATION TO ADD OR EDIT PROJECT. (30-11-2023)
                $project->coc = $request->COC;
                $project->updated_at = date('Y-m-d H:i:s');
                $project->save();


                if ($request->building_type == 'House') {
                    $counter = count($request->houseType);
                    for ($i = 0; $i < $counter; $i++) {
                        $ProjectBuildingDetails = new ProjectBuildingDetails();
                        $ProjectBuildingDetails->projectId = $project->id;
                        $ProjectBuildingDetails->buildingType = $request->building_type;
                        $ProjectBuildingDetails->houseType = $request->houseType[$i];
                        $ProjectBuildingDetails->floorCount = $request->floorCount[$i];
                        $ProjectBuildingDetails->save();
                    }
                } elseif ($request->building_type == 'Commercial') {
                    for ($i = 0; $i < (int)$request->floorCount1; $i++) {
                        $ProjectBuildingDetails = new ProjectBuildingDetails();
                        $ProjectBuildingDetails->projectId = $project->id;
                        $ProjectBuildingDetails->buildingType = $request->building_type;
                        $ProjectBuildingDetails->floorCount = $i;
                        $ProjectBuildingDetails->save();
                    }
                } elseif ($request->building_type == 'Apartment') {
                    $ProjectBuildingDetails = new ProjectBuildingDetails();
                    $ProjectBuildingDetails->projectId = $project->id;
                    $ProjectBuildingDetails->buildingType = $request->building_type;
                    $ProjectBuildingDetails->save();
                }


                $projectFilesArray = ["Door Schedule", "Door Elevations", "Floor Plan", "NBS", "BOQ (Bill of Quantities)", "Other Files", "Ironmongery Schedule"];
                foreach ($projectFilesArray as $projectFileIndex => $projectFileVal) {
                    $filename = preg_replace('/\s+/', '', $projectFileVal);

                    $altername = $filename == 'BOQ(BillofQuantities)' ? 'BOQ' : $filename;

                    if ($request->hasfile($altername)) {
                        $i = 1;
                        foreach ($request->file($altername) as $file) {
                            $name = random_int(1000, 10000) . '_' . $file->getClientOriginalName();
                            $file->move(public_path() . '/uploads/Project/', $name);
                            $projectfiles = new ProjectFiles();
                            $projectfiles->projectId = $project->id;
                            $projectfiles->tag = $altername;
                            $projectfiles->file = $name;
                            $projectfiles->created_at = date('Y-m-d H:i:s');
                            $projectfiles->updated_at = date('Y-m-d H:i:s');
                            $projectfiles->save();
                            $i++;
                        }
                    }
                }
            }

            if (!is_null($update_val)) {
                return redirect()->route('project/list')->with('success', 'Project update successfully! ' . $msg);
            } else {
                return redirect()->route('project/list')->with('success', 'Project added successfully! ' . $msg);
            }
        } else {
            $request->session()->flash('error', 'Please fill required field!');
            return redirect()->back();
        }
    }

    public function quotation($id)
    {
        if (Auth::user()->UserType == 2) {
            $CompanyId = get_company_id(Auth::user()->id)->id;

            $quotationWithNoProject = Quotation::whereNull('ProjectId')->where(['CompanyId' => $CompanyId])->orderBy('id', 'desc')->get();
            $customer = Customer::where('UserId', Auth::user()->id)->get();
            $projects = Project::where('GeneratedKey', $id)->first();
            $quotationToTheseProject = Quotation::whereNull('ProjectId')->where(['CompanyId' => $CompanyId, 'CustomerId' => $projects->customerId])->orderBy('id', 'desc')->get();
        } else {
            $quotationWithNoProject = Quotation::whereNull('ProjectId')->orderBy('id', 'desc')->get();
            $customer = Customer::where('UserId', Auth::user()->id)->get();
            $projects = Project::where('GeneratedKey', $id)->first();
            $quotationToTheseProject = Quotation::whereNull('ProjectId')->where(['CustomerId' => $projects->customerId])->orderBy('id', 'desc')->get();
        }
        
        if (!empty($projects) && (array)$projects !== []) {
            return view('DoorSchedule.AddQuotation', ['quotationWithNoProject' => $quotationWithNoProject, 'quotationToTheseProject' => $quotationToTheseProject, 'projects' => $projects, 'customer' => $customer]);
        } else {
            return redirect()->route('project/create');
        }
    }



    public function quotationList($id)
    {


        $data = Project::leftJoin('quotation', 'quotation.ProjectId', 'project.id')->leftJoin('companies', 'companies.id', 'quotation.CompanyId')->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')->where('project.GeneratedKey', $id)->get();

        return view('Project.ProjectQuotationList', ['data' => $data]);
    }

    public function quotationListAjax(request $request): void
    {

        //echo $request->ajaxCall;die;
        if ($request->ajaxCall == 1) {

            $from = $request->from;
            $limit = $request->limit;

            if ($limit == "0" || $limit == "") {
                $limit = PHP_INT_MAX;
            }


            $filters = $request->filters;
            if ($request->filters == "") {
                $filters = [];
            }
            
            for ($i = 0; $i <= count($filters) - 1; $i++) {

                $filters[$i] = [$filters[$i][0], $filters[$i][1], $filters[$i][2]];
            }
            
            $orders = $request->orders;
            $column = $orders[0]["column"];
            $dir = $orders[0]["dir"];
        }


        //        $data = Project::leftJoin('quotation','quotation.ProjectId','project.id')->leftJoin('companies','companies.id','quotation.CompanyId')->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')->where('project.GeneratedKey',$id)->get();
        $data = Project::leftJoin('quotation', 'quotation.ProjectId', 'project.id')->leftJoin('companies', 'companies.id', 'quotation.CompanyId')->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')->where($filters)->skip($from)->take($limit)->orderBy($column, $dir)->get();



        if ((array)$data->toArray() !== []) {

            $htmlData = View::make('Project.Ajax.ProjectQuotationListAjax', ['data' => $data])->render();

            ms([
                'st' => "1",
                'txt' => 'data found.',
                'total' => count((array)$data),
                'html' => $htmlData,
            ]);
        } else {
            ms([
                'st' => "0",
                'txt' => 'data not found.',
                'total' => count((array)$data),
                'html' => "",
            ]);
        }
    }


    public function edit(request $request)
    {
        if (Auth::user()->UserType == '2') {
            if (property_exists($request, 'edit') && $request->edit !== null) {
                Session::put('edit', $request->edit);
                return redirect()->route('user/edit');
            } else {
                $id = Session::get('edit');
                if (isset($id)) {
                    $editdata = User::where('id', $id)->first();
                    return view('Users.AddUser', ['editdata' => $editdata]);
                } else {
                    return redirect()->route('user/list');
                }
            }
        }

        return null;
    }


    public function RandomString(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $randstring;
    }

    public function list(request $request)
    {
        //echo "<pre>";

        $loginUserId = Auth::user()->id;
        $loginUserType = Auth::user()->UserType;
        $login_company_id = get_company_id(Auth::user()->id)->id;

        switch ($loginUserType) {

            case 1:

                if (empty($request->id)) {

                    // $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*')->orderBy('project.id','desc')->get();
                    $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->orderBy('project.id', 'desc')->get();
                } else {

                    $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id]
                    ])->orderBy('project.id', 'desc')->get();
                }
                
                break;

            case 2:

                if (empty($request->id)) {
                    $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id', 'desc')->get();
                } else {
                    $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id],
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id', 'desc')->get();
                }


                break;

            case 4:

                $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                    ['ArchitectId', '=', $loginUserId]
                ])->orderBy('project.id', 'desc')->get();

                break;

            default:

                $data = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                    ['UserId', '=', $loginUserId]
                ])->orderBy('project.id', 'desc')->get();
        }

        //$linksArray = ["","1111","1111","1111","1111","1111"];
        //print_r(array_filter($linksArray));
        //echo "<pre>";
        //print_r($data);
        //die;

        return view('Project.ProjectList', ['data' => $data]);
    }

    public function fileUpload(request $request): void
    {
        $filepath = $request->upload_path;
        $oldfile = $request->oldfile;

        $acceptExtensions = $request->acceptExtensions;
        if (!empty($request->acceptExtensions)) {

            $acceptExtensions = explode(",", $acceptExtensions);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $fileName = rando() . $file->getClientOriginalName();
            $filepath = public_path($filepath);

            $fileExtension = $file->getClientOriginalExtension();

            if (!empty($acceptExtensions) && !in_array($fileExtension, $acceptExtensions)) {
                ms([
                    'st' => "0",
                    'txt' => 'Invalid file type',
                ]);
            }
            
            $uploaded = $file->move($filepath, $fileName);
            if ($uploaded) {
                if ($oldfile != "") {
                    File::delete($filepath . $oldfile);
                }

                ms([
                    'st' => "1",
                    'txt' => 'File uploaded successfully.',
                    'fileName' => $fileName,
                ]);
            } else {
                ms([
                    'st' => "0",
                    'txt' => 'Upload error',
                    'field' => ""
                ]);
            }
        } else {
            ms([
                'st' => "0",
                'txt' => 'Select any file.',
                'field' => []
            ]);
        }
    }

    public function addquotation(Request $request)
    {


        $valid = $request->validate([
            'selectQuotation' => 'required'
        ]);
        $id = $request->selectQuotation;
        // $qq = Quotation::where('id',$id)->first();
        // $aa = QuotationContactInformation::where('QuotationId',$id)->first();
        $co = QuotationVersion::where('quotation_id', $id)->count();
        if ($co > 0) {
            $aa = QuotationVersion::where('quotation_id', $id)->first();
            $version = $aa->id;
        } else {
            $version = 0;
        }

        $quo = Quotation::find($id);
        $quo->ProjectId = $request->ProjectId;
        $quo->editBy = Auth::user()->id;
        $quo->updated_at = date('Y-m-d H:i:s');
        // if($qq->ExpiryDate != '' && $qq->QuotationName != '' && $qq->Currency != '' && $aa->Email != ''){
        //     $quo->flag = 1;
        // } else {
        //     $quo->flag = 0;
        // }
        $quo->save();
        
        $url = url('quotation/generate/' . $id . '/' . $version);
        return redirect()->to($url);
    }


    public function addquotation2(Request $request)
    {

        $valid = $request->validate([
            'selectQuotation' => 'required'
        ]);
        $id = $request->selectQuotation;
        // $qq = Quotation::where('id',$id)->first();
        // $aa = QuotationContactInformation::where('QuotationId',$id)->first();
        $co = QuotationVersion::where('quotation_id', $id)->count();
        if ($co > 0) {
            $aa = QuotationVersion::where('quotation_id', $id)->first();
            $version = $aa->id;
        } else {
            $version = 0;
        }
        
        $quo = Quotation::find($id);
        $quo->ProjectId = $request->ProjectId;
        $quo->editBy = Auth::user()->id;
        $quo->updated_at = date('Y-m-d H:i:s');
        // if($qq->ExpiryDate != '' && $qq->QuotationName != '' && $qq->Currency != '' && $aa->Email != ''){
        //     $quo->flag = 1;
        // } else {
        //     $quo->flag = 0;
        // }
        $quo->save();
        
        $url = url('quotation/generate/' . $id . '/' . $version);
        return redirect()->to($url);
    }


    public function projectNewQuotation($projectId, $customerId = null)
    {

        $qidFromhelper = GenerateQuotationFirstTime($projectId, $customerId);
        if (Auth::user()->UserType != 4) {
            $quotaionUpdate = Quotation::where('ProjectId', $projectId)->orderBy('id', 'desc')->first();
            $quotaionUpdate->MainContractorId = $customerId;
            $quotaionUpdate->editBy = Auth::user()->id;
            $quotaionUpdate->CompanyUserId = (empty(Auth::user()->main_id))?Auth::user()->id:Auth::user()->main_id;
            $quotaionUpdate->updated_at = date('Y-m-d H:i:s');
            $quotaionUpdate->save();
        }
        
        return redirect()->route('quotation/generate/', [$qidFromhelper, 0]);
    }

    public function deleteProjectFile(Request $request): int
    {
        $projectFileID = $request->projectFileID;
        $filename = $request->filename;
        $pp = ProjectFiles::select('projectId')->where('id', $projectFileID)->first();
        $project = Project::where('id', $pp->projectId)->first();
        $project->editBy = Auth::user()->id;
        $project->updated_at = date('Y-m-d H:i:s');
        $project->save();

        $filepath = public_path('uploads/Project/');
        File::delete($filepath . $filename);
        ProjectFiles::where('id', $projectFileID)->delete();
        ProjectFilesDS::where('projectfileId', $projectFileID)->delete();
        return 1; // success
    }
}
