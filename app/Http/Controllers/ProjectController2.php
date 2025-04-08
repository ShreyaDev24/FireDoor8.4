<?php

namespace App\Http\Controllers;

use Spatie\PdfToImage\Pdf;
// use Org_Heigl\Ghostscript\Ghostscript;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Exception;
// use PDF;
use PdfMerger;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App\Models\Project;
use App\Models\ProjectBuildingDetails;
use App\Models\ProjectFiles;
use App\Models\ProjectFilesDS;
use App\Models\ProjectDefaultItems;
use App\Models\Tooltip;
use App\Models\AddIronmongery;
use App\Models\Company;
use App\Models\SelectedIronmongery;
use App\Models\IronmongeryInfoModel;
use App\Models\ConfigurableItems;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\ProjectInvitation;
use App\Models\User;
use App\Models\Quotation;
use App\Models\TeamBoard;
use App\Models\Architect;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\SurveyInfo;
use App\Models\SurveyTasks;
use App\Models\SurveyChangerequest;
use App\Models\SurveyAttachment;
use App\Models\Notification;
use App\Models\QuotationVersionItems;
use App\Models\Color;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\Floor;
use App\Models\FloorPlanDoor;
use App\Models\SurveyStatus;
use App\Models\BOMCalculation;
use App\Models\QuotationVersion;
use App\Models\IronmongeryName;
use App\Models\Option;
use App\Models\ConfigurableDoorFormula;
use App\Models\NonConfigurableItemStore;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllProjectExport;

class ProjectController2 extends Controller
{
    public function list(Request $request){

        $loginUserId = Auth::user()->id;
        $loginUserType = Auth::user()->UserType;
        $login_company_id = get_company_id(Auth::user()->id);

            switch ($loginUserType){

            case 1:

                if(empty($request->id)){

                     $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->orderBy('project.id','desc')->get();

                }else{

                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id]
                    ])->orderBy('project.id','desc')->get();

                }
                
                break;

            case 2:

                if(empty($request->id)){
                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id','desc')->get();
                }else{
                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id],
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id','desc')->get();

                }
                
                break;

                default:

                $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                    ['project.UserId', '=', $loginUserId]
                ])->orderBy('project.id','desc')->get();
            }

        return view('Project.ProjectList',['data' => $data]);
    }


    public function getProjectList(Request $request)
    {

  //dd($request->all());
        $from = $request->from;
        $limit = $request->limit;

        if($limit == "0" || $limit == ""){
            $limit = PHP_INT_MAX;
        }


        $filters = $request->filters;
        if($request->filters == ""){
            $filters = [];
        }

        for($i = 0 ; $i <= count($filters)-1; $i++){

            $filters[$i] = [$filters[$i][0],$filters[$i][1],$filters[$i][2]];

        }
        
        // $orders = $request->orders;
        // $column = $orders[0]["column"];
        // $dir = $orders[0]["dir"];
        $column = $request->column;
        $dir = $request->dir;


        $loginUserId = Auth::user()->id;

        $UserType = Auth::user()->UserType;


        switch ($UserType){

            case 1:

                if(!empty($request->id)){
                    $filters[] = ['project.UserId', "=", $request->id];
                }

                break;

            case 2:
                $login_company_id = get_company_id(Auth::user()->id)->id ?? Auth::user()->id;
                if(empty($request->id)){
                    $filters[] = ['project.ProjectStatus','=', 'Accepted'];
                    $filters[] = ['project.CompanyId', "=", $login_company_id];

                }else{
                    $filters[] = ['project.UserId', "=", $request->id];
                    $filters[] = ['project.CompanyId', "=", $login_company_id];
                }
                
                break;

            case 3:
                if(empty($request->id)){
                    $filters[] = ['project.ProjectStatus','=', 'Accepted'];
                }

                break;


            case 4:
                    $filters[] = ['project.UserId', "=", $loginUserId];

                break;

                case 5:
                    $login_customer_id = get_customer_id(Auth::user()->id)->id;
                    $filters[] = ['project.MainContractorId', "=", $login_customer_id];
                break;


            default:
                $filters[] = ['project.UserId', "=", $loginUserId];
        }



        if ($UserType == 3) {
            $login_company_id = get_company_id(Auth::user()->CreatedBy)->id ?? null;
            $filters[] = ['project.CompanyId', "=", $login_company_id];
            $created_by_my_cmpny_admin_user = myCreatedUser();
            $todays = date('Y-m-d');
            // ✅ Find if a date filter exists in $filters
            $dateFilter = collect($filters)->firstWhere(0, 'project.created_at');
            $query = Project::leftJoin('companies', 'companies.id', '=', 'project.CompanyId')
                ->select(
                    'project.*',
                    'project.updated_at as Projectupdated_at',
                    'project.id as ProjectId',
                    'companies.*',
                    DB::raw("(SELECT count(*) FROM quotation WHERE project.id = quotation.ProjectId) as quotesCount"),
                    DB::raw("(SELECT count(*) FROM quotation WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) as ordersCount")
                )
                ->where(function ($q) use ($created_by_my_cmpny_admin_user, $filters): void {
                    // ✅ Apply all filters dynamically
                    foreach ($filters as $filter) {
                        $q->where($filter[0], $filter[1], $filter[2]);
                    }
                    
                    $q->orWhereIn('project.UserId', $created_by_my_cmpny_admin_user);
                });
            // ✅ Apply date filter only if it exists
            if ($dateFilter) {
                $operator = $dateFilter[1];
                $dateValue = $dateFilter[2];

                if (!empty($dateValue) && strtotime($dateValue)) {
                    if ($dateValue == $todays) {
                        $query->whereDate('project.created_at', $todays);
                    } else {
                        $query->where('project.created_at', $operator, $dateValue);
                    }
                }
            }

            // ✅ Clone query for count
            $countProject = (clone $query)->count();
            if($request->listType=='dataListType'){
             $data = $query->orderBy($column, $dir)->get();
         }else{
 
             $data = $query->skip($from)->take($limit)->orderBy($column, $dir)->get();
         }
        } elseif ($UserType == 2) {
            $created_by_me_users = myCreatedUser();
            if (!empty($filters)) {
                $todays = date('Y-m-d');

                // Start the base query
                $query = Project::leftJoin('companies', 'companies.id', '=', 'project.CompanyId')
                    ->select(
                        'project.*',
                        'project.updated_at as Projectupdated_at',
                        'project.id as ProjectId',
                        'companies.*',
                        DB::raw("(SELECT count(*) FROM quotation WHERE project.id = quotation.ProjectId) as quotesCount"),
                        DB::raw("(SELECT count(*) FROM quotation WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) as ordersCount")
                    )
                    ->where(function ($q) use ($created_by_me_users): void {
                        $q->orWhereIn('project.UserId', $created_by_me_users);
                    });

                $dateFilter = collect($filters)->firstWhere(0, 'project.created_at');

                if ($dateFilter) {
                    $operator = $dateFilter[1];
                    $dateValue = $dateFilter[2];

                    // Apply date filter only if a valid date exists
                    if (!empty($dateValue) && strtotime($dateValue)) {
                        if ($dateValue == $todays) {
                            $query->whereDate('project.created_at', $todays);
                        } else {
                            $query->where('project.created_at', $operator, $dateValue);
                        }
                    }
                }
                
                foreach ($filters as $filter) {
                    if ($filter[0] !== "project.created_at") {
                        $query->where($filter[0], $filter[1], $filter[2]);
                    }
                }

                $countProject = (clone $query)->count();
            }

            if($request->listType=='dataListType'){
                $data = $query->orderBy($column, $dir)->get();
            }else{

                $data = $query->skip($from)->take($limit)->orderBy($column, $dir)->get();
            }
        } else{
            $countProject = Project::leftJoin('companies','companies.id','project.CompanyId')
            ->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
            ->where($filters)->count();

            $data = Project::leftJoin('companies','companies.id','project.CompanyId')
            ->select('project.*', 'project.updated_at as Projectupdated_at','project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
            ->where($filters);
            //->skip($from)->take($limit)->orderBy("$column", "$dir")->get();

            if($request->listType=='dataListType'){
                $data = $data->orderBy($column, $dir)->get();
            }else{

                $data = $data->skip($from)->take($limit)->orderBy($column, $dir)->get();
            }

        }

        if((array)$data->toArray() !== []){
            $htmlData = '';
            $DoorsetPrice = 0;
            if ($request->input('listType') == 'dataListType') {
                 // $Quotations = $Quotations->toArray();
                 $htmlData .= '<table id="dataListType" class="table table-hover table-striped table-bordered dataTable no-footer dtr-inline">
                <thead class="text-uppercase table-header-bg text-white">
                    <tr>
                        <th>S.N</th>
                        <th>Project Name</th>
                        <th>Quotation Company Name</th>
                        <th>Building Type</th>
                        <th>Files</th>
                        <th>Quotes</th>
                        <th>Orders</th>
                        <th>Ironmongery Set</th>
                        <th>Return Tender Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
                 $sn = 1;
            foreach($data as $val){
                $us = User::where('id',$val->editBy)->first();
                $custCompanyName = Customer::where('id',$val->MainContractorId)->first();
                $projectFilesCount = ProjectFiles::where('projectId',$val->ProjectId)->count();
                $CompanyName = $custCompanyName != '' ? $custCompanyName->CstCompanyName : '-----------';

                $BuildingType = $val->BuildingType != '' ? $val->BuildingType : '-----------';

                $quotesCount = $val->quotesCount != '' ? $val->quotesCount : 0;

                $ordersCount = $val->ordersCount != '' ? $val->ordersCount : 0;

                $returnTenderDate = $val->returnTenderDate != '' ? $val->returnTenderDate : '-----------';
                $lastModifier = $us != '' ? $us->FirstName.' '.$us->LastName : '';
                
                //firedoor2_role_update
                // $countIronmongerySet = AddIronmongery::where(['CompanyId' => $login_company_id , 'ProjectId' => $val->ProjectId])->count();
                $countIronmongerySet = AddIronmongery::where(['ProjectId' => $val->ProjectId])->count();

                if($val->Status == 1){
                    $projectname = '<a href="'.url('project/quotation-list/'.$val->GeneratedKey).'" class="QuotationCode">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="dropdown-item deactivateproject"><i class="fa fa-lock" style="margin-right: 8px;"></i>   Deactivate Project</a>';
                } else {
                    $projectname = '<a href="#" class="QuotationCode" style="color: black;">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="dropdown-item activateproject"><i class="fa fa-unlock-alt" style="margin-right: 8px;"></i>  Activate Project</a>';
                }


                // currency showing formate is changed accordingly(dynamically)
                $Currency = '';
                if($UserType != 4 && !empty($val->projectCurrency)){
                    if ($val->projectCurrency == '£_GBP') {
                        $Currency = "£";
                    } elseif ($val->projectCurrency == '€_EURO') {
                        $Currency = "€";
                    } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                        $Currency = "$";
                    }
                }




                $htmlData .= '<tr>
                <td>'.$sn.'</td>
                <td>'.$projectname.'</td>
                <td>'.$CompanyName.'</td>
                <td>'.ucwords($BuildingType).'</td>
                <td>'.$projectFilesCount.'</td>
                <td>'.$quotesCount.'</td>
                <td>'.$ordersCount.'</td>
                <td>'.$countIronmongerySet.'</td>
                <td>'.date2Formate($returnTenderDate).'</td>
                <td><div class="dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ....
                </button>
                <div class="dropdown-menu dropdown-list" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item delproject" href="javascript:void(0);"><i class="fa fa-trash" style="margin-right: 8px;"></i>   Delete Project</a>
                    <input type="hidden" value="'.$val->ProjectId.'">
                    '.$activedeactive.'
                    <input type="hidden" value="'.$val->ProjectId.'">
                </div>
            </div>

                </td>
            </tr>';
$sn++;


                // <div class="QuotationStatusNumber">'.$Currency .''. $totalCost .'</div>
            }
            
            $htmlData .= '</tbody>
            </table>';
        }else{

            foreach($data as $val)
            {
                // $projectList = Quotation::leftJoin("quotation_versions",function($join){
                //     $join->on("quotation.id","quotation_versions.quotation_id")
                //         ->orOn("quotation_versions.id","=","quotation.VersionId");
                // })
                // ->leftJoin("project","project.id","quotation.ProjectId")
                // ->select('quotation.*','quotation.id as QuotationId', 'project.*','quotation_versions.id as QVID')
                // ->where('ProjectId',$val->ProjectId)
                // ->get();
                // $DoorsetPrice = 0;
                // if(!empty($projectList)){
                //     foreach($projectList as $value){

                //         $QVID = $value['QVID'] != ""?$value['QVID']:0;
                //         $Item = Item::Join('quotation','quotation.id','=','items.QuotationId')->
                //         // Join('item_master','item_master.itemID','=','items.itemId')->
                //         leftJoin("quotation_version_items",function($join) use ($QVID){
                //             $join->on("quotation_version_items.itemID","=","items.itemId")
                //                 // ->on("quotation_version_items.itemmasterID","=","item_master.id")
                //                 ->where("quotation_version_items.version_id","=",$QVID);
                //         })->where('quotation.QuotationGenerationId',$value->QuotationGenerationId)->where('items.VersionId',$QVID)->get();
                //         if(!empty($Item)){
                //             foreach($Item as $data){
                //                 $DoorsetPrice = $DoorsetPrice + ((($data->AdjustPrice)?floatval($data->AdjustPrice) :floatval($data->DoorsetPrice)) + $data->IronmongaryPrice);
                //             }
                //         }
                //         $discountPrice = ($DoorsetPrice + nonConfigurableItem($value->QuotationId,$QVID,Auth::user()->id,'',true)) * $value->QuoteSummaryDiscount/100;
                //         $DoorsetPrice = ($DoorsetPrice + nonConfigurableItem($value->QuotationId,$QVID,Auth::user()->id,'',true)) - $discountPrice;
                //     }
                // }

                $us = User::where('id',$val->editBy)->first();
                $custCompanyName = Customer::where('id',$val->MainContractorId)->first();
                $projectFilesCount = ProjectFiles::where('projectId',$val->ProjectId)->count();
                $CompanyName = $custCompanyName != '' ? $custCompanyName->CstCompanyName : '-----------';

                $BuildingType = $val->BuildingType != '' ? $val->BuildingType : '-----------';

                $quotesCount = $val->quotesCount != '' ? $val->quotesCount : 0;

                $ordersCount = $val->ordersCount != '' ? $val->ordersCount : 0;

                $returnTenderDate = $val->returnTenderDate != '' ? $val->returnTenderDate : '-----------';
                $lastModifier = $us != '' ? $us->FirstName.' '.$us->LastName : '';
                
                //firedoor2_role_update
                // $countIronmongerySet = AddIronmongery::where(['CompanyId' => $login_company_id , 'ProjectId' => $val->ProjectId])->count();
                $countIronmongerySet = AddIronmongery::where(['ProjectId' => $val->ProjectId])->count();

                if($val->Status == 1){
                    $projectname = '<a href="'.url('project/quotation-list/'.$val->GeneratedKey).'" class="QuotationCode">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="deactivateproject"><i class="fa fa-lock"></i> Deactivate Project</a>';
                } else {
                    $projectname = '<a href="#" class="QuotationCode" style="color: black;">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="activateproject"><i class="fa fa-unlock-alt"></i> Activate Project</a>';
                }


                // currency showing formate is changed accordingly(dynamically)
                $Currency = '';
                if($UserType != 4 && !empty($val->projectCurrency)){
                    if ($val->projectCurrency == '£_GBP') {
                        $Currency = "£";
                    } elseif ($val->projectCurrency == '€_EURO') {
                        $Currency = "€";
                    } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                        $Currency = "$";
                    }
                }

                $htmlData .=
                '<div class="col-sm-3 mb-3">
                    <div class="QuotationBox">
                        '.$projectname.'
                        <div class="QuotationCompanyName">
                            <b>'.$CompanyName.'</b>
                        </div>

                        <div class="QuotationListData">
                            <b>Building Type</b>
                            <span>'.ucwords($BuildingType).'</span>
                            <b>Project Name</b>
                            <span>'.ucwords($val->ProjectName).'</span>
                            <b>Files</b>
                            <span>'.$projectFilesCount.'</span>
                            <b>Quotes</b>
                            <span>'.$quotesCount.'</span>
                            <b>Orders</b>
                            <span>'.$ordersCount.'</span>
                            <b>Ironmongery Set</b>
                            <span>'.$countIronmongerySet.'</span>
                            <b>Return Tender Date</b>
                            <span>'.date2Formate($returnTenderDate).'</span>
                        </div>
                        <div class="QuotationListNumber"></div>
                        <div class="QuotationModifiedDate">
                            <p>Last modified by '.$lastModifier.' on '.dateFormate($val->Projectupdated_at).'</p>
                        </div>
                        <div class="filter_action">
                            <label for="filter" class="quote_filter">
                                <i class="fas fa-ellipsis-h"></i>
                            </label>
                            <ul class="QuotationMenu">
                                <li><a href="javascript:void(0);" class="delproject"><i class="fa fa-trash"></i> Delete Project</a>
                                    <input type="hidden" value="'.$val->ProjectId.'">
                                </li>
                                <li>
                                    '.$activedeactive.'
                                    <input type="hidden" value="'.$val->ProjectId.'">
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>';
            }

        }

            // <div class="QuotationStatusNumber">'.$Currency.floatval($DoorsetPrice).'</div>
            // <li><a href="'.route('addironmongery',[$val->ProjectId]).'"><i class="fa fa-shield"></i> Add Ironmongery Set</a></li>

            // return $htmlData;
            return [
                'st' => "success",
                'txt' => 'data found.',
                'total' => $countProject,
                'html' => $htmlData,
            ];


        } else {
            $htmlData = 'Data not found.';
            return [
                'st' => "error",
                'txt' => 'Data not found.',
                'total' => 0,
                'html' => $htmlData,
            ];


        }

    }

    //gettting project details
    //getting company architect customer list
    //check condition for architect assigned quotation
    public function getProjectDetails($id){
        $markAsReadNotification = markAsRead($id, 'project');

        $data = Project::leftJoin('quotation','quotation.ProjectId','project.id')
        ->leftJoin('companies','companies.id','quotation.CompanyId')
        ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')

            ->where('project.GeneratedKey',$id)
            ->get();

            $pro = Project::where('GeneratedKey',$id)->first();
            $projectId = $pro->id;
            $qid = $pro->quotationId;
            $vid = $pro->versionId;
            $buildingType = $pro->BuildingType;
            $quotation_limit_for_arch = Quotation::where('UserId',Auth::user()->id)->where('ProjectId',$projectId)->count();


            // send list of main contractor
            // $main_contractors = ['john@gmail.com','lashn@gmail.com','pankaj@resiliencesoft.com','kunal@resiliencesoft.com','shailesh@resiliencesoft.com'];
            // dd($data->toArray());


        //getting company architect customer list
        $company_list = Company::join('users','users.id','companies.UserId')->select('companies.id','companies.CompanyName','users.UserEmail')->where('users.UserType',2)->orderBy('companies.CompanyName')->get();
        $architect_list = Architect::join('users','users.id','architects.UserId')
                                    ->select('architects.ArcCompanyName','architects.id','users.UserEmail')
                                    ->orderBy('architects.ArcCompanyName')
                                    ->get();

        if(Auth::user()->UserType==1){
        $main_contractor_list = Customer::join('users','users.id','customers.UserId')
                                ->select('customers.id','users.UserEmail','customers.CstCompanyName')
                                ->where('users.CreatedBy',Auth::user()->id)->orderBy('customers.CstCompanyName')->get();
        }
        else{
        $main_contractor_list = Customer::join('users','users.id','customers.UserId')
                                ->select('customers.id','users.UserEmail','customers.CstCompanyName')
                                ->orderBy('customers.CstCompanyName')
                                ->get();
        }


        //check condition for company assigned quotation
        //    $get_company_id = get_company_id(Auth::user()->id);

        //     $assigned_project = Project::join('quotation','quotation.ProjectId','project.id')->select('QuotationGenerationId')
        //     ->where('project.CompanyId',$get_company_id->id)
        //     ->where('project.id',$projectId)
        //     ->value('QuotationGenerationId');


        $company_name = '';
        $architect_name = '';
        $main_contractor_name = '';
        $project = Project::where('id',$projectId)->first();


        $company_name = Company::where('id',$project->CompanyId)->value('CompanyName');

        $architect_name = Architect::where('id',$project->ArchitectId)->value('ArcCompanyName');

        $main_contractor_name = Customer::where('id',$project->MainContractorId)->value('CstCompanyName');
        // dd($main_contractor_list);
        return view('Project.ProjectQuotationList',['data' => $data, 'projectId' => $projectId, 'quotation_limit_for_arch' => $quotation_limit_for_arch, 'main_contractor_list' => $main_contractor_list, 'architect_list' => $architect_list, 'company_list' => $company_list, 'company_name' => $company_name, 'architect_name' => $architect_name, 'main_contractor_name' => $main_contractor_name, 'vid' => $vid, 'qid' => $qid, 'buildingType' => $buildingType]);
    }

    public function invite(Request $request)
    {
        try {
            $get_architect_id = get_architect_id(Auth::user()->id)->id;
            $data = [];
            $emails = $request->email;
            if ($request->type == 'email') {
                // Take email Id ;
                $emailId = Customer::select('id')->where('CstCompanyEmail', $emails)->where('UserType',
                'maincontractor')->first();
                $get_contractor_id = get_customer_id($emailId->id)->id;
                $data = [
                    'MainContractorId' =>  $get_contractor_id,
                    'ProjectId' => $request->projectId,
                    'UserId' => Auth::user()->id,
                    'ArchitectId' => $get_architect_id
                ];
            } else {
                foreach ($emails as $key => $emailId) {
                $get_contractor_id = get_customer_id($emailId)->id;
                    $data[] = [
                        'MainContractorId' => $get_contractor_id,
                        'ProjectId' => $request->projectId,
                        'UserId' => Auth::user()->id,
                        'ArchitectId' => $get_architect_id
                    ];
                }
            }

            $createInvitation = ProjectInvitation::insert($data);
            if ($createInvitation) {
                return redirect()->back()->with('success', 'Invitation sent successfully! ');
            }
            
            return redirect()->back()->with('error', 'Failed Invitation not sent. Please retry! ');

        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Server error, Invitation not sent. Please contact admin! ');
        }
    }

    public function invitation_list(Request $request)
    {

        // dd("ok");
        $loginUserId = Auth::user()->id;
        $loginUserType = Auth::user()->UserType;



            switch ($loginUserType){

            case 1:

                if(empty($request->id)){

                    // $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*')->orderBy('project.id','desc')->get();
                     $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->orderBy('project.id','desc')->get();

                }else{

                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id]
                    ])->orderBy('project.id','desc')->get();

                }
                
                break;

            case 2:

                if(empty($request->id)){
                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id','desc')->get();
                }else{
                    $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                        ['UserId', '=', $request->id],
                        ['CompanyId', '=', $login_company_id]
                    ])->orderBy('project.id','desc')->get();

                }
                
                break;

                default:

                $data = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->where([
                    ['project.UserId', '=', $loginUserId]
                ])->orderBy('project.id','desc')->get();
            }

        return view('Contractor.ProjectInvitationList');
    }


    public function invitation_records(Request $request)
    {
        $from = $request->from;
        $limit = $request->limit;

        if($limit == "0" || $limit == ""){
            $limit = PHP_INT_MAX;
        }


        $filters = $request->filters;
        if($request->filters == ""){
            $filters = [];
        }

        for($i = 0 ; $i <= count($filters)-1; $i++){

            $filters[$i] = [$filters[$i][0],$filters[$i][1],$filters[$i][2]];

        }
        
        // $orders = $request->orders;
        // $column = $orders[0]["column"];
        // $dir = $orders[0]["dir"];
        $column = $request->column;
        $dir = $request->dir;


        $loginUserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        $get_contractor_id = get_customer_id($loginUserId)->id;



        $filters[] = ['project_invitations.Status', "=", Null];

        // Take all project where this contractor is invite to :
        $invitedProjects = ProjectInvitation::leftJoin('customers','customers.UserId','project_invitations.MainContractorId')
        ->select('project_invitations.ProjectId')->where($filters)->pluck('ProjectId')->toArray();

        // $countProject = Project::leftJoin('companies','companies.id','project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
        // ->where($filters)->count();


        $data = Project::leftJoin('companies','companies.id','project.CompanyId')
        ->join('project_invitations','project_invitations.ProjectId','project.id')
        ->leftJoin('customers','customers.UserId','project_invitations.MainContractorId')
        ->select('project.*', 'project_invitations.id as invitation_id', 'project.updated_at as Projectupdated_at','project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
        ->where('project.Status', "=", 1)
        ->where($filters)
        ->where('project_invitations.MainContractorId',$get_contractor_id)
        // ->whereIn('project.id', $invitedProjects)
        ->skip($from)->take($limit)->orderBy($column, $dir)->distinct()->get();//->where($filters)->skip($from)->take($limit)->orderBy("$column", "$dir")->get();

        $countProject = count(json_decode(json_encode($data), true));
        if((array)$data->toArray() !== []){
            $htmlData = '';
            foreach($data as $val)
            {
                $us = User::where('id',$val->editBy)->first();
                $custCompanyName = Customer::where('id',$val->MainContractorId)->first();
                $projectFilesCount = ProjectFiles::where('projectId',$val->ProjectId)->count();
                $CompanyName = $custCompanyName != '' ? $custCompanyName->CstCompanyName : '-----------';

                $quotesCount = $val->quotesCount != '' ? $val->quotesCount : 0;

                $ordersCount = $val->ordersCount != '' ? $val->ordersCount : 0;

                $returnTenderDate = $val->returnTenderDate != '' ? $val->returnTenderDate : '-----------';
                $lastModifier = $us != '' ? $us->FirstName.' '.$us->LastName : '';
                
                //firedoor2_role_update

                $countIronmongerySet = AddIronmongery::where(['ProjectId' => $val->ProjectId])->count();

                if($val->Status == 1){
                    $projectname = '<a href="'.url('project/quotation-list/'.$val->GeneratedKey).'" class="QuotationCode">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="deactivateproject"><i class="fa fa-lock"></i> Deactivate Project</a>';
                } else {
                    $projectname = '<a href="#" class="QuotationCode" style="color: black;">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="activateproject"><i class="fa fa-unlock-alt"></i> Activate Project</a>';
                }


                // currency showing formate is changed accordingly(dynamically)
                $Currency = '';
                if(!empty($val->projectCurrency)){
                    if ($val->projectCurrency == '£_GBP') {
                        $Currency = "£ GBP";
                    } elseif ($val->projectCurrency == '€_EURO') {
                        $Currency = "€ EURO";
                    } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                        $Currency = "$ US DOLLAR";
                    }
                }
                
                $htmlData .=
                '<div class="col-sm-3 mb-3">
                    <div class="QuotationBox">
                        '.$projectname.'
                        <div class="QuotationCompanyName">
                            <b>'.$CompanyName.'</b>
                        </div>
                        <div class="QuotationStatusNumber">'.$Currency.'</div>
                        <div class="QuotationListData">
                            <b>Project Name</b>
                            <span>'.ucwords($val->ProjectName).'</span>
                            <b>Files</b>
                            <span>'.$projectFilesCount.'</span>
                            <b>Quotes</b>
                            <span>'.$quotesCount.'</span>
                            <b>Orders</b>
                            <span>'.$ordersCount.'</span>
                            <b>Ironmongery Set</b>
                            <span>'.$countIronmongerySet.'</span>
                            <b>Return Tender Date</b>
                            <span>'.date2Formate($returnTenderDate).'</span>
                        </div>
                        <div class="QuotationListNumber"></div>
                        <div class="QuotationModifiedDate">
                            <p>Last modified by '.$lastModifier.' on '.dateFormate($val->Projectupdated_at).'</p>
                        </div>
                        <div class="filter_action">
                            <label for="filter" class="quote_filter">
                                <i class="fas fa-ellipsis-h"></i>
                            </label>
                            <ul class="QuotationMenu">
                                <li><a href="javascript:void(0);" class="acceptproject"><i class="fa fa-check-square"></i> Accept Project</a>
                                    <input type="hidden" value="'.$val->invitation_id.'">
                                </li>
                                <li><a href="javascript:void(0);" class="rejectproject"><i class="fa fa-eject"></i> Reject Project</a>
                                    <input type="hidden" value="'.$val->invitation_id.'">
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>';
            }

            // return $htmlData;
            return [
                'st' => "success",
                'txt' => 'data found.',
                'total' => $countProject,
                'html' => $htmlData,
            ];


        } else {
            $htmlData = 'Data not found.';
            return [
                'st' => "error",
                'txt' => 'Data not found.',
                'total' => 0,
                'html' => $htmlData,
            ];

        }

    }

    public function invitation_status(Request $request)
    {
        // dd($request->all());
        try {
            // $valid = $request->validate([
            //     'ProjectImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // ]);
            $data = [];
            $projectId = $request->projectId;
            $choice = $request->choice;
            $data = [
                'Status' => $choice,
            ];
            if ($request->message) {
                $data['descriptive_message'] = $request->message;
            }

            if($request->hasFile('document')){
                $file = $request->file('document');
                $FileOriName = rando().$file->getClientOriginalName();
                $filepath = public_path('uploads/Project/invitation/');
                $ImageExtension = $file->getClientOriginalExtension();
                $ext = pathinfo($filepath.$FileOriName , PATHINFO_EXTENSION);
                $fileName = time().$FileOriName;
                if( in_array($ext, ['pdf','docx', 'xls']) )
                {
                    $file->move($filepath,$fileName);
                    // File::delete($filepath.$project->ProjectImage);
                    $data['document_file'] = $fileName;
                }
            }

            ProjectInvitation::where('id', $projectId)->update($data);
            return redirect()->back()->with('success', sprintf('Invitation %s successfully! ', $choice));
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Server error, while updating invitation status. Please contact admin! ');
        }
    }

    public function project_assign(Request $request)
    {
        try {
            $data = [];
            $projectId = $request->projectId;
            $contractor = $request->maincontratorId;

            $project_invitation = ProjectInvitation::where('id',$request->project_invitation_id)->first();
            $project_invitation->Status = 'awarded';
            $project_invitation->save();

            $data = [
                'MainContractorId' => $project_invitation->MainContractorId,
                'MainContractorId'=>$project_invitation->MainContractorId,
                'ProjectStatus'=>'AssignedToMainContractor'
            ];
            Project::where('id', $projectId)->update($data);

            return redirect()->back()->with('success', "Project Assign successfully!");
        } catch (Exception $exception) {
            return $exception;
            return redirect()->back()->with('error', 'Server error, while assigning project to main contractor. Please contact admin! ');
        }
    }


    //add comment
    public function addComment(Request $request) {
        if(!empty($request->message)){
            $message = $request->message;
            $projectId = $request->projectId;
            $user = auth()->user();

            $teamBoard = new TeamBoard();
            $teamBoard->ProjectId = $projectId;
            $teamBoard->UserId = $user->id;
            $teamBoard->Message = $message;
            $teamBoard->created_at = date('Y-m-d H:i:s');
            $teamBoard->updated_at = date('Y-m-d H:i:s');
            $teamBoard->save();

            $teamboards = TeamBoard::where('ProjectId',$projectId)->get();

            foreach ($teamboards as $comment) {
                $comment->time = Carbon::parse($comment->created_at)->diffForHumans();
                $comment->user = User::all()->where('id',$comment->UserId)->first();
            }


            return response()->json(['status' => 'success',
                'teamboards'=>$teamboards]);
        }

        return null;
    }



    public function quotationListAjax2(Request $request){
        $from = $request->from;
        $limit = $request->limit;

        if(Auth::user()->UserType == 2){
            $users = User::where('UserType',3)->where('CreatedBy',Auth::user()->id)->pluck('id');
            $user_ids = [];
            foreach($users as $valUserId){
                $user_ids[] = $valUserId;
            }
            
            $user_ids[] = Auth::user()->id;
        }elseif(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();

            $user_ids = [Auth::user()->id, intval($users->CreatedBy)];
        }else{
            $user_ids = [Auth::user()->id];
        }

        if($limit == "0" || $limit == ""){
            $limit = PHP_INT_MAX;
        }

        $filters = $request->filters;
        if($request->filters == ""){
            $filters = [];
        }

        for($i = 0 ; $i <= count($filters)-1; $i++){

            $filters[$i] = [$filters[$i][0],$filters[$i][1],$filters[$i][2]];

        }

        // $orders = $request->orders;
        // $column = $orders[0]["column"];
        // $dir = $orders[0]["dir"];
        $column = 'quotation.created_at';
        // $dir = 'desc';
        $dir = $request->dir;
        $useTbl = auth()->user();


        // $data = Project::leftJoin('quotation','quotation.ProjectId','project.id')
        //     ->leftJoin('companies','companies.id','quotation.CompanyId')
        //     ->select('quotation.*','quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
        //     ->where($filters)->skip($from)->take($limit)->orderBy("$column", "$dir")
        //     ->get();

        $data = Quotation::leftJoin("quotation_versions",function($join): void{
            $join->on("quotation.id","quotation_versions.quotation_id")
                ->orOn("quotation_versions.id","=","quotation.VersionId");
        })
        ->leftJoin("project","project.id","quotation.ProjectId")
        ->leftJoin('companies','companies.id','quotation.CompanyId')
        ->leftJoin('customers','customers.UserId','quotation.MainContractorId')
        ->select('quotation.*','quotation.MainContractorId as MainContractorsId','quotation.editBy as QuotEditBy','quotation.updated_at as QuotUpdatedAt', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*','quotation_versions.id as QVID','customers.CstCompanyName')
        ->where($filters)
        ->skip($from)
        ->take($limit)
        ->orderBy($column, $dir)
        ->get();

        $projectinfo = Project::where($filters)->first();


            $projectId = $request->projectId;

            $buildingDetails = ProjectBuildingDetails::where('projectId',$projectId)->get();

            //handling of comments
            $teamboards = TeamBoard::where('ProjectId',$projectId)->orderBy('id','desc')->get();

            foreach ($teamboards as $comment) {
                $comment->time = Carbon::parse($comment->created_at)->diffForHumans();
                $comment->user = User::all()->where('id',$comment->UserId)->first();
            }


            //end handling of comments
            //firedoor2_role_update


            $addIronmongery = AddIronmongery::where(['ProjectId'=>$projectId])->get();
            $i = 1;
            $tbl = '';
            foreach($addIronmongery as $t){
                $tbl .=
                '<tr>
                    <td>'.$t->Setname.'</td>
                    <td>
                        <button type="button" value="'.$t->id.'" class="btn btn-info updAddIronmongery">Edit</button>
                        <button type="button" value="'.$t->id.'" class="btn btn-danger delAddIronmongery" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</button>
                    </td>
                </tr>';
                $i++;
            }
            
            $tbl2 = '';
            if(!empty($projectinfo)){
                $cc = CustomerContact::join('customers','customers.id','customer_contacts.MainContractorId')->where('customers.UserId',$projectinfo->customerId)->get();
                foreach($cc as $ccs){
                    $tbl2 .=
                    '<tr>
                        <td>'.$ccs->FirstName.' '.$ccs->LastName.'</td>
                        <td>'.$ccs->ContactEmail.'</td>
                        <td>'.$ccs->ContactPhone.'</td>
                        <td>'.$ccs->ContactJobtitle.'</td>
                    </tr>';
                }
            }


        $ProjectFiles = ProjectFiles::where('projectId',$projectId)->get();
        $user = $useTbl;
        // Add by caca
        $alreadyInvitedList = ProjectInvitation::select('MainContractorId')->where('ProjectId',$projectId)->pluck('MainContractorId')->toArray();

        $main_contractors_full = Customer::join('users','users.id','customers.UserId')
                                        ->select('customers.id','customers.UserId','customers.CstCompanyEmail as email', 'customers.CstCompanyName as name')
                                        ->where('users.CreatedBy',Auth::user()->id)->whereNotIn('customers.id',$alreadyInvitedList)
                                        ->get()->toArray();
        $main_contractors = Customer::select('CstCompanyEmail')->whereNotIn('id',$alreadyInvitedList)->pluck('CstCompanyEmail')->toArray();
        // Invited contrator
        $invitedContractors = ProjectInvitation::leftJoin('customers','customers.id','project_invitations.MainContractorId')
        ->select('project_invitations.*','project_invitations.id as invitationId','project_invitations.created_at as invitedAt','customers.*')->where('ProjectId',$projectId)->get()->toArray();

        $tbl3 = "";

        $check_award_someone = ProjectInvitation::leftJoin('customers','customers.id','project_invitations.MainContractorId')
        ->select('project_invitations.*','project_invitations.id as invitationId','project_invitations.created_at as invitedAt','customers.*')->where('ProjectId',$projectId)->where('Status','awarded')->count();


        if(!empty($invitedContractors)){
            $ii = 0;
            foreach($invitedContractors as $row){

                $MainContractorDetails = CustomerContact::where('MainContractorId',$row['id'])->orderBy('id','asc')->first();

                $customer = Customer::where('id',$row['id'])->orderBy('id','asc')->first();

                $FirstName = "";
                $LastName = "";
                $ContactEmail = "";
                $ContactPhone = "";

                if($MainContractorDetails !== null){
                    $FirstName = $MainContractorDetails->FirstName;
                    $LastName = $MainContractorDetails->LastName;
                    $ContactEmail = $customer->CstCompanyEmail;
                    $ContactPhone = $MainContractorDetails->ContactPhone;
                }

                $tbl3 .= '<tr>';
                $tbl3 .= '<td><a href="'.url('contractor/details/'.$row['id']).'">'.$row['CstCompanyName'].'</a></td>
                    <td>'.$FirstName.' '.$LastName.' </td>
                    <td>'.$ContactEmail.'</td>
                    <td>'.$ContactPhone.'</td>
                    <td>'.$row['CstCompanyAddressLine1'].'</td>
                    <td>'.$row['invitedAt'].'</td>';

                if ($row['Status'] == 'accepted') {
                    $mes= is_null($row['descriptive_message']) ? "No comment added" : $row['descriptive_message'];
                    $mes_file = is_null($row['document_file']) ? "No File uploaded" : '<a href="'.asset('uploads/Project/invitation/'.$row['document_file']).'" target="_blank">
                    <img max-width="150px" max-height="150px" src="'.asset('assets/images/doc.png').'"></a>';
                    $tbl3 .= '<td class="text-success">Accepted</td>';
                    $tbl3 .= '<td><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#docModal'.$ii.'"><i class="fa fa-eye"></i></a>';
                    $tbl3 .= '   <div class="modal fade" tabindex="-1" role="dialog" id="docModal'.$ii.'" data-backdrop="false">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Invitation response details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" >
                                            <h3> Comment : </h3>
                                            <hr>
                                            <p> '. $mes . ' </p>
                                            <h3> Uploaded document : </h3>
                                            <hr>
                                            '. $mes_file .'
                                        </div>
                                        </div>
                                    </div>
                                </div>';
                    if($check_award_someone==0){
                        $tbl3 .= '<input type="hidden" name="invitationId" class="invitationId" value='.$row['invitationId'].'><a href="javascript:void(0);" class="btn btn-success assignproject"><i class="fa fa-check"></i></a><input type="hidden" value="'.$row['MainContractorId'].'"></td>';
                    }
                    else{
                        $tbl3 .= '</td>';
                    }
                } elseif ($row['Status'] == 'rejected') {
                    $tbl3 .= '<td class="text-danger">Rejected</td>';
                    // $tbl3 .= '<td><a href="'.url('contractor/edit/'.$row['id']).'" class="btn btn-success"><i class="fa fa-pencil"></i></a></td>';
                } elseif ($row['Status'] == 'awarded') {
                    $tbl3 .= '<td class="text-primary">Awarded</td>';
                    // $tbl3 .= '<td><a href="'.url('contractor/edit/'.$row['id']).'" class="btn btn-success"><i class="fa fa-pencil"></i></a></td>';
                } else {
                    $tbl3 .= '<td class="text-warning">Pending</td>';
                    // $tbl3 .= '<td><a href="'.url('contractor/edit/'.$row['id']).'" class="btn btn-success"><i class="fa fa-pencil"></i></a></td>';
                }


                $tbl3 .= '</tr>';
                $ii++;
            }
        }else{
            $tbl3 .= '<tr>';
            $tbl3 .= '<td colspan="7">No record found.</td>';
            $tbl3 .= '</tr>';
        }

        //survey users
        $survey_user_table = '';
        $survey_user = '';
        $survey_user_count = '';
        $us = '';
        if(!empty($data[0])){
            $survey_user = User::select('*')->where('UserType',6)->wherein('CreatedBy',$user_ids)->where('status','1')->get();

            $survey_user_count = User::select('*')->where('UserType',6)->wherein('CreatedBy',$user_ids)->where('status','1')->count();

            if(isset($survey_user) && !empty($survey_user) && $survey_user_count > 0){
                $survey_user_table .='<div class="table-overflow"><table class="table"><tbody>';
                $row = 1;
                foreach($survey_user as $ccs){
                    $selectedOption = SurveyInfo::where(['userId' => $ccs->id,'projectId'=>$projectId])->wherein('companyId',$user_ids)->count();
                    $select = $selectedOption > 0 ? 'checked' : '';

                    $survey_user_table .=
                    '<tr>
                        <td>'.$row++.'</td>
                        <td>'.$ccs->FirstName.' '.$ccs->LastName.'</td>
                        <td>'.$ccs->UserEmail.'</td>
                        <td>'.$ccs->UserPhone.'</td>
                        <td>'.$ccs->UserJobtitle.'</td>
                        <td><input type="checkbox" class="survey_users" value="'.$ccs->id.'" '.$select.'/></td>
                    </tr>';
                }
                
                $survey_user_table .=  '</tbody></table></div>';
                $survey_user_table .= '<button type="button" onclick="updateMe()" class="btn btn-primary text-white checkupdate-btn">Select</button>';

                $survey_user_table .= '<input type="hidden" id = "QuotationId" value = "'.$data[0]->QuotationId.'">';
            }else{
                $survey_user_table .= '<p> No users available </p>';
            }
        }

        //survey change Request
        $survey_change_request = '';
        if(!empty($data[0])){
            $SurveyChangerequestcount = SurveyChangerequest::join('items','items.itemId','=','survey_changerequest.itemId')->join('users','users.id','=','survey_changerequest.userId')->where(['survey_changerequest.projectId' => $request->projectId])->wherein('survey_changerequest.companyId',$user_ids)->count();

            $SurveyChangerequest = SurveyChangerequest::join('items','items.itemId','=','survey_changerequest.itemId')->join('users','users.id','=','survey_changerequest.userId')->join('item_master','item_master.id','=','survey_changerequest.itemMasterId')->select('survey_changerequest.*','users.FirstName','users.LastName','items.SOWidth as oldSOWidth','items.SOHeight as oldSOHeight','items.SOWallThick as oldSODepth','items.DoorType','survey_changerequest.oldSOWidth as oldestSOWidth','survey_changerequest.oldSOHeight as oldestSOHeight','survey_changerequest.oldSODepth as oldestSODepth','item_master.doorNumber')->where(['survey_changerequest.projectId' => $request->projectId])->wherein('survey_changerequest.companyId',$user_ids)->get();

            if(isset($SurveyChangerequest) && !empty($SurveyChangerequest) && $SurveyChangerequestcount > 0){
                $survey_change_request .='<div class="table-overflow"><table class="table mt-4"><th>S.No.</th><th>Servey User</th><th>Door Type</th><th>Door Number</th><th>Old SOWidth</th><th>Old SOHeight</th><th>Old SODepth</th><th>New SOWidth</th><th>New SOHeight</th><th>New SODepth</th><th>Action</th><tbody>';
                $row = 1;
                $disabled = '';
                foreach($SurveyChangerequest as $ccs){
                    $disabled = '';
                    if($ccs->status == 2){
                        $disabled = 'disabled';
                        $ccs->oldSOWidth = $ccs->oldestSOWidth;
                        $ccs->oldSOHeight = $ccs->oldestSOHeight;
                        $ccs->oldSODepth = $ccs->oldestSODepth;
                    }
                    
                    $survey_change_request .=
                    '<tr>
                        <td>'.$row++.'</td>
                        <td>'.$ccs->FirstName.' '.$ccs->LastName.'</td>
                        <td>'.$ccs->DoorType.'</td>
                        <td>'.$ccs->doorNumber.'</td>
                        <td>'.$ccs->oldSOWidth.'</td>
                        <td>'.$ccs->oldSOHeight.'</td>
                        <td>'.$ccs->oldSODepth.'</td>
                        <td>'.$ccs->SOWidth.'</td>
                        <td>'.$ccs->SOHeight.'</td>
                        <td>'.$ccs->SODepth.'</td>
                        <td><button type="button" onclick="ChangeRequest('.$data[0]->quotationId.','.$data[0]->versionId.','.$ccs->SOWidth.','.$ccs->SOHeight.','.$ccs->itemId.','.$ccs->itemMasterId.','.$ccs->oldSOWidth.','.$ccs->oldSOHeight.','.$ccs->oldSODepth.','.$ccs->SODepth.','.$ccs->id.')" class="btn btn-primary text-white" '.$disabled.'>Accept Request</button></td>
                    </tr>';
                }
                
                $survey_change_request .=  '</tbody></table></div>';
            }else{
                $survey_change_request .= '<p> No requests available </p>';
            }
        }

        //survey tasks
        $survey_tasks = '';
        if(!empty($data[0])){
            $SurveyTasksCount = SurveyTasks::where(['projectId' => $request->projectId])->wherein('companyId',$user_ids)->count();

            $SurveyTasks = SurveyTasks::where(['projectId' => $request->projectId])->wherein('companyId',$user_ids)->get();

            if(isset($SurveyTasks) && !empty($SurveyTasks) && $SurveyTasksCount > 0){
                $survey_tasks .='<div class="table-overflow"><table class="table mt-4">
                <th>S.No.</th>
                <th style="text-align: center;">Tasks</th>
                <th class="text-right">Action </th>
                <tbody>';
                $row = 1;
                foreach($SurveyTasks as $ccs){
                    $length = strlen($ccs->tasks);
                    $btn = '';
                    if($length >=  260){
                        $btn = '<button class="more_less">show more..</button>';
                    }

                    $survey_tasks .=
                    '<tr>
                        <td>'.$row++.'</td>
                        <td class="text-center"><p class="content_text showText">'.$ccs->tasks.'</p>'.$btn.'</td>
                        <td><button type="button" onclick="taskUpdate(\''.$ccs->tasks."',".$ccs->id.')" class="btn btn-primary text-white float-right">Edit</button></td>
                    </tr>';
                }
                
                $survey_tasks .=  '</tbody></table></div>';
            }else{
                $survey_tasks .= '<p> No tasks available </p>';
            }
        }

        //survey user schedule
        $survey_info_table = '';
        if(!empty($data[0])){

            $survey_user_info = SurveyInfo::join('users','users.id','survey_info.UserId')
                                        ->select('survey_info.*', 'users.*','survey_info.id as user_id')
                                        ->wherein('survey_info.companyId',$user_ids)
                                        ->where('survey_info.projectId', $projectId)
                                        ->get();

            if(isset($survey_user_info) && !empty($survey_user_info)){
                $survey_info_table .='<div class="table-overflow"><table class="table"><th>S.No.</th><th>Name</th><th>Start Date</th><th>End Date</th><th>Action</th><tbody>';
                $row = 1;

                foreach($survey_user_info as $info){
                    $min_date = date('Y-m-d\TH:i');
                    if(isset($info->fromTime) && !empty($info->fromTime)){
                        $info->fromTime = date('Y-m-d\TH:i', strtotime($info->fromTime));
                    }
                    
                    if(isset($info->toTime) && !empty($info->toTime)){
                        $info->toTime = date('Y-m-d\TH:i', strtotime($info->toTime));
                    }
                    
                    $survey_info_table .=
                    '<tr>
                        <td>'.$row++.'</td>
                        <td>'.$info->FirstName.' '.$info->LastName.'</td>
                        <td><input type="datetime-local" class="from_date" id="from_date'.$info->user_id.'" value="'.$info->fromTime.'" min="'.$min_date.'"/></td>
                        <td><input type="datetime-local" class="to_date" id="to_date'.$info->user_id.'" value="'.$info->toTime.'" min="'.$min_date.'"/></td>
                        <td><button type="button" onclick="updateFromToDate('.$info->user_id.','.$projectId.')" class="btn btn-primary text-white scheduleupdate-btn">schedule</button></td>
                    </tr>';
                }

                $survey_info_table .=  '</tbody></table></div>';

                $survey_info_table .= '<input type="hidden" id = "QuotationId" value = "'.$data[0]->QuotationId.'">';
            }else{
                $survey_info_table .= '<p> No users available </p>';
            }
        }

        //survey attachments
        $survey_attachments_table = '';
        if(!empty($data[0])){
            $SurveyAttachmentsCount = SurveyAttachment::where(['projectId' => $request->projectId])->wherein('companyId',$user_ids)->count();

            $SurveyAttachments = SurveyAttachment::where(['projectId' => $request->projectId])->wherein('companyId',$user_ids)->orderBy('id','DESC')->get();

            if(isset($SurveyAttachments) && !empty($SurveyAttachments) && $SurveyAttachmentsCount > 0){
                $survey_attachments_table .='<div class="table-overflow"><table class="table mt-4">
                <th>S.No.</th>
                <th>Attachments</th>
                <th><p class="float-right">Action</p></th>
                <tbody>';
                $row = 1;
                foreach($SurveyAttachments as $ccs){
                    $survey_attachments_table .=
                    '<tr>
                        <td>'.$row++.'</td>
                        <td>'.$ccs->attachment.'</td>
                        <td><p class="float-right"><a href="'.url('Survey_attachment/'.$ccs->attachment).'" target="_blank" class="btn btn-primary"><i class="fas fa-eye"></i> View</a></p></td>
                    </tr>';
                }
                
                $survey_attachments_table .=  '</tbody></table></div>';
            }else{
                $survey_attachments_table .= '<p> No Attachments available </p>';
            }
        }

        //['john@gmail.com','lashn@gmail.com','pankaj@resiliencesoft.com','kunal@resiliencesoft.com','shailesh@resiliencesoft.com'];

        //survey Dashboard
        $totalcount = Project::join("quotation_versions",function($join): void{
            $join->on("quotation_versions.id","=","project.versionId")
                ->on("quotation_versions.quotation_id","=","project.quotationId");
        })
        ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
        ->join('survey_status','quotation_version_items.itemmasterID','survey_status.itemMasterId')
        ->select('survey_status.*')->where('project.id',$projectId)->groupBy('survey_status.itemMasterId')->get()->count();

        $pending = Project::join("quotation_versions",function($join): void{
                        $join->on("quotation_versions.id","=","project.versionId")
                            ->on("quotation_versions.quotation_id","=","project.quotationId");
                    })
                    ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
                    ->join('survey_status','quotation_version_items.itemmasterID','survey_status.itemMasterId')
                    ->select('survey_status.*')->where('survey_status.status',1)->where('project.id',$projectId)->groupBy('survey_status.itemMasterId')->get()->count();

        $inProgress = Project::join("quotation_versions",function($join): void{
                        $join->on("quotation_versions.id","=","project.versionId")
                            ->on("quotation_versions.quotation_id","=","project.quotationId");
                    })
                    ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
                    ->join('survey_status','quotation_version_items.itemmasterID','survey_status.itemMasterId')
                    ->select('survey_status.*')->where('survey_status.status',2)->where('project.id',$projectId)->groupBy('survey_status.itemMasterId')->get()->count();

        $completed = Project::join("quotation_versions",function($join): void{
                    $join->on("quotation_versions.id","=","project.versionId")
                        ->on("quotation_versions.quotation_id","=","project.quotationId");
                })
                ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
                ->join('survey_status','quotation_version_items.itemmasterID','survey_status.itemMasterId')
                ->select('survey_status.*')->where('survey_status.status',3)->where('project.id',$projectId)->groupBy('survey_status.itemMasterId')->get()->count();

        $floorPlans = Project::join("quotation_versions",function($join): void{
            $join->on("quotation_versions.id","=","project.versionId")
                ->on("quotation_versions.quotation_id","=","project.quotationId");
        })
        ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
        ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
        ->join('items','items.itemId','item_master.itemID')
        ->select('item_master.*','items.*')->where('project.id',$projectId)->groupBy('item_master.id')->get();

        if(!empty($data[0]->QuotEditBy)){
            $us = User::where('id',$data[0]->QuotEditBy)->first();
        }

        $option_data = Option::where(['configurableitems' => 1, 'is_deleted' => 0])->wherein('editBy', CompanyUsers())->get();

        $configurationDoor = configurationDoor(1);
        $intumescentSealColor = GetOptions(['intumescent_seal_color.Status' => 1], "join", "intumescent_seal_color");
        $ArchitraveType = GetOptions(['architrave_type.Status' => 1], "join", "architrave_type");
        $selected_option_data = GetOptions(['options.configurableitems' => 1, 'options.is_deleted' => 0, 'selected_option.SelectedUserId' => Auth::user()->id], "join");
        $ConfigurableDoorFormula = ConfigurableDoorFormula::where('status', 1)->get();
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $defaultItems = Project::with(['defaultItems' => function ($query) use ($projectId, $ids): void {
            $query->where('UserId',  $ids) // Simplified for Auth user
                  ->where('projectId', $projectId);
        }])
        ->whereHas('defaultItems', function ($query) use ($projectId, $ids): void {
            $query->where('UserId',  $ids) // Simplified for Auth user
                  ->where('projectId', $projectId);
        })
        ->first();

        $defaultItemsCustom = "";
        $defaultItemsStandard = "";
        // Retrieve 'custom' and 'standard' items separately
        if(!empty($defaultItems)){
            $defaultItemsCustom = $defaultItems->defaultItems->firstWhere('default_type', 'custom') ?? [];
        }
        
        if(!empty($defaultItems)){
            $defaultItemsStandard = $defaultItems->defaultItems->firstWhere('default_type', 'standard') ?? [];
        }

        $color_data = Color::where('Status', 1)->where('editBy', Auth::id())->get();

        $lipping_species = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        $selected_lipping_species = $lipping_species;

        // if(count((array)$data->toArray()) > 0){
            $htmlData = view('Project.Ajax.ProjectQuotationListAjax',['data' => $data, 'tbl' => $tbl, 'tbl2' => $tbl2, 'ProjectFiles' => $ProjectFiles, 'main_contractors' => $main_contractors, 'main_contractors_full' => $main_contractors_full, 'tbl3' => $tbl3, 'projectId' => $projectId, 'user' => $user, 'teamboards' => $teamboards, 'survey_user_table' => $survey_user_table, 'survey_user' => $survey_user, 'survey_user_count' => $survey_user_count, 'survey_info_table' => $survey_info_table, 'pending' => $pending, 'inProgress' => $inProgress, 'completed' => $completed, 'totalcount' => $totalcount, 'survey_tasks' => $survey_tasks, 'survey_change_request' => $survey_change_request, 'survey_attachments_table' => $survey_attachments_table, 'floorPlans' => $floorPlans, 'buildingDetails' => $buildingDetails, 'us' => $us, 'projectinfo' => $projectinfo, 'option_data' => $option_data, 'intumescentSealColor' => $intumescentSealColor, 'ArchitraveType' => $ArchitraveType, 'selected_option_data' => $selected_option_data, 'ConfigurableDoorFormula' => $ConfigurableDoorFormula, 'defaultItemsCustom' => $defaultItemsCustom, 'defaultItemsStandard' => $defaultItemsStandard, 'color_data' => $color_data, 'lipping_species' => $lipping_species, 'selected_lipping_species' => $selected_lipping_species])->render();
            return $htmlData;
    }

    public function getFloorPlanDoors(request $request): void{

        if($request->floorplan_category_text == ''){
            $floorPlans = Project::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","project.versionId")
                    ->on("quotation_versions.quotation_id","=","project.quotationId");
            })
            ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
            ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
            ->join('items','items.itemId','item_master.itemID')
            ->leftJoin('floor_plan_doors','floor_plan_doors.itemId','item_master.id')
            ->select('item_master.doorNumber','item_master.id as itemMasterId', 'items.itemID', 'items.FireRating', 'items.DoorType',  'items.QuotationId','floor_plan_doors.*')->where('project.id',$request->projectId)->groupBy('item_master.id')->get();
        }else{
            $floorPlans = Project::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","project.versionId")
                    ->on("quotation_versions.quotation_id","=","project.quotationId");
            })
            ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
            ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
            ->join('items','items.itemId','item_master.itemID')
            ->leftJoin('floor_plan_doors','floor_plan_doors.itemId','item_master.id')
            ->select('item_master.doorNumber','item_master.id as itemMasterId', 'items.itemID', 'items.FireRating', 'items.DoorType',  'items.QuotationId','floor_plan_doors.*')->where('project.id',$request->projectId)->where('item_master.floor',$request->floorplan_category_text)->groupBy('item_master.id')->get();
        }


        if($floorPlans){
            echo json_encode(["status" => "success", "data" => $floorPlans, "msg" => "Data found Successfully!"]);
        }
        
        // else{
        //     echo json_encode(array("status" => "error", "data" => $floorPlans, "msg" => "Data not found!"));

        // }

    }

    //add addFloorPlanDoor
    public function addFloorPlanDoor(Request $request) {

        // $user = auth()->user();

        $FloorPlanDoor = new FloorPlanDoor();
        $FloorPlanDoor->itemId = $request->doorId;
        $FloorPlanDoor->latPosition = $request->lat;
        $FloorPlanDoor->lngPosition = $request->lng;

        if($FloorPlanDoor->save()){

            $floorPlan = FloorPlanDoor::join('item_master','item_master.id','floor_plan_doors.itemId')
            ->join('items','items.itemId','item_master.itemID')
            ->select('item_master.doorNumber','item_master.id as itemMasterId', 'items.itemID', 'items.FireRating', 'items.DoorType',  'items.QuotationId','floor_plan_doors.*')->where('floor_plan_doors.id', $FloorPlanDoor->id)->first();
            // $floorPlan = FloorPlanDoor::join('items','items.itemId','floor_plan_doors.itemId')
            // ->join('item_master','item_master.itemID','items.itemId')
            // ->select('item_master.doorNumber', 'items.itemID', 'items.FireRating', 'items.DoorType',  'items.QuotationId','floor_plan_doors.*')->where('floor_plan_doors.id', $FloorPlanDoor->id)->first();
            if($floorPlan){
                return response()->json(["status" => "success", "data" => $floorPlan, "msg" => "Door added Successfully!"]);
            }else{
                return response()->json(["status" => "error", "data" => $floorPlan, "msg" => "Something is wrong!"]);
            }

        }else{
            return response()->json(["status" => "error", "data" => [], "msg" => "Something is wrong!"]);
        }

    }

    //removeFloorPlanDoor
    public function removeFloorPlanDoor(Request $request) {

        // $user = auth()->user();

        $FloorPlanDoor = FloorPlanDoor::where('itemId',$request->id);

        if($FloorPlanDoor->delete()){
            return response()->json(["status" => "success", "data" => [], "msg" => "Door removed Successfully!"]);
        }else{
            return response()->json(["status" => "error", "data" => [], "msg" => "Something is wrong!"]);
        }

    }



    public function updateChangeRequest(request $request): void{
        $Items = Item::where(['itemId' => $request->itemId])->count();
        $itemCount = Item::join('item_master','item_master.itemID','items.itemId')->where('items.itemId',$request->itemId)->get()->count();
        if(!empty($request->id)){
            if($itemCount == 1){
                $Item = Item::where('itemId',$request->itemId)->update(['SOWidth' => $request->SOWidth,
                'SOHeight'=>$request->SOHeight,'SOWallThick'=>$request->SOWallThick]);

                $SurveyChangerequest = SurveyChangerequest::find($request->requestId);
                if(!empty($SurveyChangerequest)){
                    $SurveyChangerequest->status = 2;
                    $SurveyChangerequest->oldSOWidth = $request->oldSOWidth;
                    $SurveyChangerequest->oldSOHeight = $request->oldSOHeight;
                    $SurveyChangerequest->oldSODepth = $request->oldSODepth;
                    $SurveyChangerequest->save();
                }
                
                \Session::flash('status', 'success');
                \Session::flash('message', 'Change_request');
                echo json_encode(["status" => "success", "msg" => "Request Updated Successfully!"]);
            }elseif($itemCount > 1){
                $quotationId = $request->quotationId;
                $VersionId = $request->versionId;
                $itemID = $request->id;
                $Quotation = Quotation::find($quotationId);
                if($Quotation != null){

                    if(!empty($itemID)){

                        // if(!empty($VersionId)){
                            $OldQuotationItems = Item::select('items.*')->join('item_master','item_master.itemID','items.itemId')->where('items.QuotationId',$quotationId)->where('item_master.id',$itemID)->get()->first();
                            $ItemMasterOld = ItemMaster::where(['id' => $itemID])->get()->first();
                            ItemMaster::where(['id' => $itemID])->get()->first();

                            $door = $OldQuotationItems->DoorType.'/'.$ItemMasterOld->doorNumber;
                            $mm =  Item::where(['QuotationId' => $quotationId , 'DoorType' => $door])->get()->first();
                            if (!empty($mm) && $OldQuotationItems->DoorType . '/' . $ItemMasterOld->doorNumber == $mm->DoorType) {
                                $errorlist = 'Door Type '.$mm->DoorType.' is already exist for these quotation.';
                                \Session::flash('status', 'success');
                                \Session::flash('message', 'Change_request');
                                echo json_encode(["status" => "error", "msg" => $errorlist]);
                                exit();
                            }

                            //insert into item table
                            $Items = $OldQuotationItems->replicate();
                            $Items->DoorType = $OldQuotationItems->DoorType.'/'.$ItemMasterOld->doorNumber;
                            $Items->itemId = Item::max('itemId') + 1;
                            $Items->SOWidth = $request->SOWidth;
                            $Items->SOHeight = $request->SOHeight;
                            $Items->SOWallThick = $request->SOWallThick;
                            $Items->save();

                            $NewQuotationItemId = $Items->itemId;
                            $NewDoorType = $Items->DoorType;

                            //insert into item master table
                            $itemmaster = $ItemMasterOld->replicate();
                            $itemmaster->id = ItemMaster::max('id') + 1;
                            $itemmaster->itemID = $NewQuotationItemId;
                            $itemmaster->save();

                            if($VersionId > 0){
                                //insert into quotation version item table
                                $qv = new QuotationVersionItems();
                                $qv->version_id = $VersionId;
                                $qv->itemID = $NewQuotationItemId;
                                $qv->itemmasterID = $itemmaster->id;
                                $qv->Status = 1;
                                $qv->created_at = date('Y-m-d H:i:s');
                                $qv->updated_at = date('Y-m-d H:i:s');
                                $qv->save();
                            }
                            
                            //get version id for check the version exist or not in bom calculation table
                            $version_id = QuotationVersion::where('quotation_id', $OldQuotationItems->QuotationId)->where('id', $VersionId)->value('version');

                            if($VersionId == 0){
                                $BOMCalculation = BOMCalculation::where('QuotationId',$OldQuotationItems->QuotationId)->where('itemId',$OldQuotationItems->itemId)->get();
                            }else{
                                $BOMCalculation = BOMCalculation::where('QuotationId',$OldQuotationItems->QuotationId)->where('itemId',$OldQuotationItems->itemId)->where('VersionId',$version_id)->get();
                            }
                            
                            //insert into bom calculation table
                            if($BOMCalculation != null){
                                foreach($BOMCalculation as $IKey => $IVal){
                                    $BOMCalculationItems = $IVal->replicate();
                                    $BOMCalculationItems->id = BOMCalculation::max('id') + 1;
                                    $BOMCalculationItems->itemId = $NewQuotationItemId;
                                    $BOMCalculationItems->DoorType = $NewDoorType;
                                    $BOMCalculationItems->save();
                                }
                            }
                            
                            //insert into survey change request
                            $SurveyChangerequest = SurveyChangerequest::find($request->requestId);
                            if(!empty($SurveyChangerequest)){
                                $SurveyChangerequest->status = 2;
                                $SurveyChangerequest->oldSOWidth = $request->oldSOWidth;
                                $SurveyChangerequest->oldSOHeight = $request->oldSOHeight;
                                $SurveyChangerequest->oldSODepth = $request->oldSODepth;
                                $SurveyChangerequest->itemId = $Items->itemId;
                                $SurveyChangerequest->itemMasterId = $itemmaster->id;
                                $SurveyChangerequest->save();
                                //insert into SurveyStatus request
                                $surveyStatus = new SurveyStatus();
                                $surveyStatus->projectId = $SurveyChangerequest->projectId;
                                $surveyStatus->quotationId = $quotationId;
                                $surveyStatus->versionId = $VersionId;
                                $surveyStatus->status = 3;
                                $surveyStatus->itemId = $NewQuotationItemId;
                                $surveyStatus->itemMasterId = $itemmaster->id;
                                $surveyStatus->save();
                            }

                            $FloorPlanDoor = FloorPlanDoor::where('itemId',$request->id)->first();
                            if(!empty($FloorPlanDoor)){
                                $FloorPlanDoor->itemId = $itemmaster->id;
                                $FloorPlanDoor->save();
                            }

                            //deleting the previous stored data
                            ItemMaster::where(['id' => $request->id])->delete();
                            SurveyStatus::where(['itemId' => $request->itemId,'itemMasterId' => $request->id])->delete();
                            QuotationVersionItems::where(['itemID' => $request->itemId,'itemmasterID' => $request->id])->delete();

                            \Session::flash('status', 'success');
                            \Session::flash('message', 'Change_request');
                        echo json_encode(["status" => "success", "msg" => "Request Updated Successfully!"]);
                    }else{
                        echo json_encode(["status" => "error", "msg" => "Something went wrong!"]);
                    }

                }else{
                    echo json_encode(["status" => "error", "msg" => "Something went wrong!"]);
                }
            }
        }
    }

    public function updateTasks(request $request): void{

        $SurveyTasks = SurveyTasks::where(['companyId' => Auth::user()->id, 'projectId' => $request->projectId])->count();
        if(!empty($request->id)){
            if($SurveyTasks > 0){
                $updateSurveyTasks = SurveyTasks::find($request->id);
                if(!empty($updateSurveyTasks)){
                    $updateSurveyTasks->tasks = $request->task_input;
                    $updateSurveyTasks->save();
                    echo json_encode(["status" => "success", "msg" => "Task Updated Successfully!"]);
                }
            }
        }else{
            $newSurveyTasks = new SurveyTasks();
            $newSurveyTasks->companyId = Auth::user()->id;
            $newSurveyTasks->projectId = $request->projectId;
            $newSurveyTasks->tasks = $request->task_input;
            $newSurveyTasks->save();
            echo json_encode(["status" => "success", "msg" => "Task Created Successfully!"]);
        }
        
        \Session::flash('status', 'success');
        \Session::flash('message', 'Tasks');
    }

    // Create Attachment
    public function updateAttachment(request $request): void{

        $newSurveyAttachment = new SurveyAttachment();
        $newSurveyAttachment->companyId = Auth::user()->id;
        $newSurveyAttachment->projectId = $request->projectId;


        if($request->hasFile('file')){
            $file = $request->file('file');
            $name = time().$file->getClientOriginalName();
            $filepath = public_path('Survey_attachment');
            $file->move($filepath,$name);
            $newSurveyAttachment->attachment = $name;

            $newSurveyAttachment->save();



        echo json_encode(["status" => "success", "msg" => "Attachments Created Successfully!"]);
        }
        else{
            echo json_encode(["status" => "False", "msg" => "Attachments Failed to upload!"]);
        }

        \Session::flash('status', 'success');
        \Session::flash('message', 'Attachments');
    }

    public function projectQuotationSurvey(request $request): void{
        // dd($request->all());
        $Project = Project::where(['UserId' => Auth::user()->id, 'id' => $request->projectId])->get()->first();
        $updateSurveyInfo = Project::find($Project->id);

        SurveyInfo::where('companyId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        SurveyTasks::where('companyId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        SurveyAttachment::where('companyId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        SurveyChangerequest::where('companyId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        SurveyChangerequest::where('companyId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        Floor::where('userId' , Auth::user()->id)->where('projectId',$request->projectId)->delete();
        SurveyStatus::where('projectId',$request->projectId)->delete();

        if(!empty($updateSurveyInfo)){
            $updateSurveyInfo->quotationId = $request->quotationId;
            $updateSurveyInfo->versionId = $request->versionId;
            $updateSurveyInfo->save();
        }

        $QuotationVersionItems = QuotationVersionItems::where('version_id', $request->versionId)->get();
            if(count($QuotationVersionItems) > 0){
                foreach($QuotationVersionItems as  $QuotationVersionItem){
                    $itemmasterID[] = $QuotationVersionItem->itemmasterID;
                }
                
                $ItemMasters = ItemMaster::wherein('id',$itemmasterID)->select('floor')->groupBy('floor')->get();
                // dd($ItemMasters);
                foreach($ItemMasters as  $ItemMaster){
                    if(!empty($ItemMaster->floor)){
                        $floor = new Floor();
                        $floor->floor_name = $ItemMaster->floor;
                        $floor->projectId = $request->projectId;
                        $floor->versionId = $request->versionId;
                        $floor->quotationId = $request->quotationId;
                        $floor->userId = Auth::user()->id;
                        $floor->save();
                    }
                }
            }

        echo json_encode(["status" => "success", "msg" => "Quotation updated successfully!"]);
    }

    public function updateCheckedOption(request $request): void
    {
        if(Auth::user()->UserType == 2){
            $users = User::where('UserType',3)->where('CreatedBy',Auth::user()->id)->pluck('id');
            $user_ids = [];
            foreach($users as $valUserId){
                $user_ids[] = $valUserId;
            }
            
            $user_ids[] = Auth::user()->id;
        }elseif(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();

            $user_ids = [Auth::user()->id, intval($users->CreatedBy)];
        }else{
            $user_ids = [Auth::user()->id];
        }
        
        $keys = $request->selectedValue;
        if (!empty($keys) && count($keys)) {
            $data = SurveyInfo::wherein('companyId', $user_ids)->where('projectId',$request->projectId)->get();
            if(!empty($data)){
                SurveyInfo::wherein('companyId', $user_ids)->where('projectId',$request->projectId)->whereNotIn('userId', $keys)->delete();
            }

            foreach ($keys as $key) {
                $electedOption = [];
                $electedOption = User::select('*')->Where('id', $key)->where('UserType',6)->wherein('CreatedBy',$user_ids)->where('status','1')->first();
                $selectedOptionExist = SurveyInfo::where(['projectId'=> $request->projectId, 'userId' => $key])->wherein('companyId',$user_ids)->first();
                if (!empty($electedOption) && (array) $electedOption !== [] && empty($selectedOptionExist)) {
                    $selectedOption = new SurveyInfo();
                    $selectedOption->userId = $electedOption->id;
                    $selectedOption->projectId = $request->projectId;
                    $selectedOption->companyId = Auth::user()->id;
                    $selectedOption->save();

                    $Notification = new Notification();
                    $Notification->surveyInfoId = $selectedOption->id;
                    $Notification->surveyUserId = $electedOption->id;
                    $Notification->message = 'A new event has been scheduled for you ......';
                    $Notification->projectId = $request->projectId;
                    $Notification->save();
                }
            }


            $projectDetails = Project::join('quotation_version_items', 'quotation_version_items.version_id', 'project.versionId')->where('project.id',$request->projectId)->select('project.*','project.id as pId','quotation_version_items.*')->get();
            $SurveyStatusList = SurveyStatus::where('projectId',$request->projectId)->first();
            if(empty($SurveyStatusList)){
                foreach($projectDetails as $value){
                    $surveyStatus = new SurveyStatus();
                    $surveyStatus->projectId = $request->projectId;
                    $surveyStatus->quotationId = $value->quotationId;
                    $surveyStatus->versionId = $value->versionId;
                    $surveyStatus->status = 1;
                    $surveyStatus->itemId = $value->itemID;
                    $surveyStatus->itemMasterId = $value->itemmasterID;
                    $surveyStatus->save();

                }
            }
        }
        else{
            SurveyInfo::wherein('companyId' , $user_ids)->where('projectId',$request->projectId)->delete();
        }
        
        echo json_encode(["status" => "ok", "msg" => "Survey User Updated!"]);

        \Session::flash('status', 'success');
        \Session::flash('message', 'Users');
    }


    public function updateFromToDate(request $request): void
    {
        if (!empty($request->user_id) && !empty($request->projectId)) {
            $updateSurveyInfo = SurveyInfo::find($request->user_id);
            if(!empty($updateSurveyInfo)){
                $updateSurveyInfo->fromTime = $request->fromTime;
                $updateSurveyInfo->toTime = $request->toTime;
                $updateSurveyInfo->save();

                $users = User::where('id',$updateSurveyInfo->userId)->where('UserType',6)->get()->first();
                $project = Project::where('id',$request->projectId)->get()->first();
                $emailTo = $users->UserEmail;
                $subject = 'Survey assigned';
                $emailFrom = 'noreply@jfds.co.uk';
                $usermname = $users->FirstName.' '.$users->LastName;
                $data_set = ['usermname'=>$usermname,'projectName'=>$project->ProjectName, 'fromTime'=>$request->fromTime, 'toTime'=>$request->toTime];

                ini_set('display_errors', 1);
                try{
                    Mail::send(['html' => 'Mail.Survey'], $data_set, function($message) use(&$emailTo, &$subject, &$emailFrom): void {

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
        
        \Session::flash('status', 'success');
        \Session::flash('message', 'Schedule');

        echo json_encode(["status" => "success", "msg" => "Schedule Updated!"]);
    }

    public function ironmongeryList(Request $request){
        if (Auth::user()->UserType == 2) {
            $myAdminGroup = getMyCreatedAdmins();
            $UserId = $myAdminGroup;
            // $UserId = array_merge(['1'], $myAdminGroup);
        }else{
           $User = Auth::user();

           $UserId = ['id' => $User->id];
        }
        
        $addIronmongery = AddIronmongery::wherein('UserId',$UserId)->orderBy('Setname','ASC')->get();
        $i = 1;
        $tbl = '';
        foreach($addIronmongery as $t){
            $tbl .=
            '<tr>
                <td>'.$t->Setname.'</td>
                <td>
                    <button type="button" value="'.$t->id.'" class="btn btn-info updAddIronmongery">Edit</button>
                    <button type="button" value="'.$t->id.'" class="btn btn-danger delAddIronmongery" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</button>
                </td>
            </tr>';
            $i++;
        }

        if($tbl !== '' && $tbl !== '0'){
            $htmlData = view('Project.IronmongeryList',['tbl' => $tbl])->render();
            return $htmlData;
        } else {
            $tbl .=  '<tr><td>Data not found</td></tr>';
            $htmlData = view('Project.IronmongeryList',['tbl' => $tbl])->render();
            return $htmlData;
        }
    }

    public function addironmongery($pid)
    {
        $item = null;
        $hinge = null;
        $FloorSpring = null;
        $tooltip = Tooltip::first();
        return view('Project.addironmongery',['tooltip' => $tooltip, 'pid' => $pid, 'item' => $item, 'hinge' => $hinge, 'FloorSpring' => $FloorSpring]);
    }

    //globle ironmongery set
    public function ironmongeryadd()
    {
        $item = null;
        $hinge = null;
        $FloorSpring = null;
        $tooltip = Tooltip::first();
        $list = IronmongeryName::where('status',1)->orderBy('name','ASC')->get();
        //dd($item, $tooltip, $hinge);
        return view('Project.addIronmongeryNew',['tooltip' => $tooltip, 'item' => $item, 'hinge' => $hinge, 'FloorSpring' => $FloorSpring, 'list' => $list]);
        // return view('Project.addironmongery',compact('tooltip','pid','item','hinge' , 'FloorSpring','LocksAndLatches', 'FlushBolts' , 'ConcealedOverheadCloser' , 'PullHandles' , 'PushHandles' , 'KickPlates' ,'DoorSelectors' ,'PanicHardware', 'Doorsecurityviewer' , 'Morticeddropdownseals' , 'Facefixeddropseals' , 'ThresholdSeal' , 'AirTransferGrill' , 'Letterplates' , 'CableWays' , 'SafeHinge' , 'LeverHandle'  , 'DoorSinage'  , 'FaceFixedDoorCloser' , 'Thumbturn'  , 'KeyholeEscutchen'));
    }
    
    public function subaddironmongery(Request $request)
    {
        if(!empty($request->Setname)){

        $valid = $request->validate([
            'Setname' => 'required',
            'discountprice' => 'required'
        ]);
        $useTbl = auth()->user();
        $item = null;
        $hinge = null;
        $FloorSpring = null;
        $update_val = $request->addironmongeryID;
        if(!is_null($update_val)){
            $item = AddIronmongery::find($update_val);
        } else {
            $item = new AddIronmongery;
            $item->created_at = date('Y-m-d H:i:s');
        }
        
        $item->configurableitems = $request->configurableitems;
        $item->UserId = Auth::user()->id;
        $item->ProjectId = $request->ProjectId;
        $item->Setname = $request->Setname;
        $item->Hinges = implode(',',array_filter($request->HingesValue, 'strlen'));
        $item->hingesQty = implode(',',array_filter($request->hingesQty, 'strlen'));
        $item->FloorSpring = implode(',',array_filter($request->FloorSpringValue, 'strlen'));
        $item->floorSpringQty = implode(',',array_filter($request->floorSpringQty, 'strlen'));
        $item->LocksAndLatches = implode(',',array_filter($request->lockesAndLatchesValue, 'strlen'));
        $item->lockesAndLatchesQty = implode(',',array_filter($request->lockesAndLatchesQty, 'strlen'));
        $item->FlushBolts = implode(',',array_filter($request->flushBoltsValue, 'strlen'));
        $item->flushBoltsQty = implode(',',array_filter($request->flushBoltsQty, 'strlen'));
        $item->ConcealedOverheadCloser = implode(',',array_filter($request->concealedOverheadCloserValue, 'strlen'));
        $item->concealedOverheadCloserQty = implode(',',array_filter($request->concealedOverheadCloserQty, 'strlen'));
        $item->PullHandles = implode(',',array_filter($request->pullHandlesValue, 'strlen'));
        $item->pullHandlesQty = implode(',',array_filter($request->pullHandlesQty, 'strlen'));
        $item->PushHandles = implode(',',array_filter($request->PushHandlesValue, 'strlen'));
        $item->pushHandlesQty = implode(',',array_filter($request->pushHandlesQty, 'strlen'));
        $item->KickPlates = implode(',',array_filter($request->kickPlatesValue, 'strlen'));
        $item->kickPlatesQty = implode(',',array_filter($request->kickPlatesQty, 'strlen'));
        $item->DoorSelectors = implode(',',array_filter($request->doorSelectorsValue, 'strlen'));
        $item->doorSelectorsQty = implode(',',array_filter($request->doorSelectorsQty, 'strlen'));
        $item->PanicHardware = implode(',',array_filter($request->panicHardwareValue, 'strlen'));
        $item->panicHardwareQty = implode(',',array_filter($request->panicHardwareQty, 'strlen'));
        $item->Doorsecurityviewer = implode(',',array_filter($request->doorSecurityViewerValue, 'strlen'));
        $item->doorSecurityViewerQty = implode(',',array_filter($request->doorSecurityViewerQty, 'strlen'));
        $item->Morticeddropdownseals = implode(',',array_filter($request->morticeddropdownsealsValue, 'strlen'));
        $item->morticeddropdownsealsQty = implode(',',array_filter($request->morticeddropdownsealsQty, 'strlen'));
        $item->Facefixeddropseals = implode(',',array_filter($request->facefixeddropsealsValue, 'strlen'));
        $item->facefixeddropsealsQty = implode(',',array_filter($request->facefixeddropsealsQty, 'strlen'));
        $item->ThresholdSeal = implode(',',array_filter($request->thresholdSealValue, 'strlen'));
        $item->thresholdSealQty = implode(',',array_filter($request->thresholdSealQty, 'strlen'));
        $item->AirTransferGrill = implode(',',array_filter($request->airtransfergrillsValue, 'strlen'));
        $item->airtransfergrillsQty = implode(',',array_filter($request->airtransfergrillsQty, 'strlen'));
        $item->Letterplates = implode(',',array_filter($request->LetterplatesValue, 'strlen'));
        $item->letterplatesQty = implode(',',array_filter($request->letterplatesQty, 'strlen'));
        $item->CableWays = implode(',',array_filter($request->cableWaysValue, 'strlen'));
        $item->cableWaysQty = implode(',',array_filter($request->cableWaysQty, 'strlen'));
        $item->SafeHinge = implode(',',array_filter($request->safeHingeValue, 'strlen'));
        $item->safeHingeQty = implode(',',array_filter($request->safeHingeQty, 'strlen'));
        $item->LeverHandle = implode(',',array_filter($request->LeverHandleValue, 'strlen'));
        $item->leverHandleQty = implode(',',array_filter($request->leverHandleQty, 'strlen'));
        $item->DoorSinage = implode(',',array_filter($request->DoorSignageValue, 'strlen'));
        $item->doorSignageQty = implode(',',array_filter($request->doorSignageQty, 'strlen'));
        $item->FaceFixedDoorCloser = implode(',',array_filter($request->FaceFixedDoorClosersValue, 'strlen'));
        $item->faceFixedDoorClosersQty = implode(',',array_filter($request->faceFixedDoorClosersQty, 'strlen'));
        $item->Thumbturn = implode(',',array_filter($request->thumbturnValue, 'strlen'));
        $item->thumbturnQty = implode(',',array_filter($request->thumbturnQty, 'strlen'));
        $item->KeyholeEscutchen = implode(',',array_filter($request->keyholeEscutcheonValue, 'strlen'));
        $item->keyholeEscutcheonQty = implode(',',array_filter($request->keyholeEscutcheonQty, 'strlen'));
        $item->DoorStops = implode(',',array_filter($request->DoorStopsValue, 'strlen'));
        $item->DoorStopsQty = implode(',',array_filter($request->DoorStopsQty, 'strlen'));
        $item->Cylinders = implode(',',array_filter($request->CylindersValue, 'strlen'));
        $item->CylindersQty = implode(',',array_filter($request->CylindersQty, 'strlen'));
        $item->totalprice = $request->totalprice;
        $item->discountprice = $request->discountprice;
        $item->updated_at = date('Y-m-d H:i:s');
        $item->save();

        $project = Project::where('id', $request->ProjectId)->first();
        // $project->editBy = Auth::user()->id;
        // $project->updated_at = date('Y-m-d H:i:s');
        // $project->save();
        if(!is_null($update_val)){
            if(!empty($request->ProjectId)){
                return redirect('/project/quotation-list/'.$project->GeneratedKey)->with('success', 'Ironmongery updated successfully!');
            }
            
            return redirect('project/ironmongery-list')->with('success', 'Ironmongery updated successfully!');
        }
        else
        {
            if(!empty($request->ProjectId)){
                return redirect('/project/quotation-list/'.$project->GeneratedKey)->with('success', 'Ironmongery added successfully!');
            }
            
            return redirect('project/ironmongery-list')->with('success', 'Ironmongery added successfully!');

        }
    }else{
        $request->session()->flash('error','Please fill required field!');
        return redirect()->back();
        }
  }


    public function change_assign_project(request $request){

        $ProjectId= $request->ProjectId;

        $CompanyId= $request->CompanyId;
        // return   $ProjectId;
        $ArchitectId= $request->ArchitectId;
        $MainContractorId= $request->MainContractorId;
        $data=Project::where('id', $request->ProjectId)->first();

        // return  $data;
        if(!empty($CompanyId)){
           $data->CompanyId=$CompanyId;
           $data->ProjectStatus = 'InvitedToCompany';
        }
        elseif(!empty($ArchitectId)){
            $data->ArchitectId=$ArchitectId;
         }
        else{
            $data->MainContractorId=$MainContractorId;
         }
        
        // dd($data);
         $data->save();
         return redirect()->back();
    }

    public function updAddIronmongery(Request $request)
    {
        $id = $request->updAddIronmongery;
        $tooltip = Tooltip::first();
        $item = AddIronmongery::where('id',$id)->first();

        $pid = $item->ProjectId;
        // $pageId = $item->configurableitems;
        $useTbl = auth()->user();
        $CompanyId = get_company_id(Auth::user()->id)->id;



        // $selectironmongery = SelectedIronmongery::where('id',$item->Hinges)->first();
        // $addIronmongery = IronmongeryInfoModel::select('Name','Code')->where('id',$selectironmongery->ironmongery_id)->first();
        // $hinge = $addIronmongery->Code.'-'.$addIronmongery->Name;
        $list = IronmongeryName::where('status',1)->orderBy('name','ASC')->get();
        $Hinges = null;
        $FloorSpring = null;
        $LocksAndLatches = null;
        $FlushBolts = null;
        $ConcealedOverheadCloser = null;
        $PullHandles = null;
        $PushHandles = null;
        $KickPlates = null;
        $DoorSelectors = null;
        $PanicHardware = null;
        $Doorsecurityviewer = null;
        $Morticeddropdownseals = null;
        $Facefixeddropseals = null;
        $ThresholdSeal = null;
        $AirTransferGrill = null;
        $Letterplates = null;
        $CableWays = null;
        $SafeHinge = null;
        $LeverHandle = null;
        $DoorSinage = null;
        $FaceFixedDoorCloser = null;
        $Thumbturn = null;
        $KeyholeEscutchen = null;
        $DoorStops = null;
        $Cylinders = null;
        $HingesPrice = null;
        $FloorSpringPrice = null;
        $LocksandLatchesPrice = null;
        $FlushBoltsPrice = null;
        $ConcealedOverheadCloserPrice = null;
        $PullHandlesPrice = null;
        $PushHandlesPrice = null;
        $KickPlatesPrice = null;
        $DoorSelectorsPrice = null;
        $PanicHardwarePrice = null;
        $DoorsecurityviewerPrice = null;
        $MorticeddropdownsealsPrice = null;
        $FacefixeddropsealsPrice = null;
        $ThresholdSealPrice = null;
        $AirTransferGrillPrice = null;
        $LetterplatesPrice = null;
        $CableWaysPrice = null;
        $SafeHingePrice = null;
        $LeverHandlePrice = null;
        $DoorSinagePrice = null;
        $FaceFixedDoorCloserPrice = null;
        $ThumbturnPrice = null;
        $KeyholeEscutchenPrice = null;
        $DoorStopsPrice = null;
        $CylindersPrice = null;

        if(!empty($item->Hinges)){
            $result = ironmongeryGetCodeName($item->Hinges);
            $Hinges = $result['name'];
            $HingesPrice = $result['price'];
            $list = $this->setValue("HingesKey", $list, $Hinges, $HingesPrice);
        }

        if(!empty($item->FloorSpring)){
            $result = ironmongeryGetCodeName($item->FloorSpring);
            $FloorSpring = $result['name'];
            $FloorSpringPrice = $result['price'];
            $list = $this->setValue("FloorSpringKey", $list, $FloorSpring, $FloorSpringPrice);
        }

        if(!empty($item->LocksAndLatches)){
            $result = ironmongeryGetCodeName($item->LocksAndLatches);
            $LocksAndLatches = $result['name'];
            $LocksandLatchesPrice = $result['price'];
            $list = $this->setValue("LocksandLatchesKey", $list, $LocksAndLatches, $LocksandLatchesPrice);
        }
        
        if(!empty($item->FlushBolts)){
            $result = ironmongeryGetCodeName($item->FlushBolts);
            $FlushBolts = $result['name'];
            $FlushBoltsPrice = $result['price'];
            $list = $this->setValue("flushBoltsKey", $list, $FlushBolts, $FlushBoltsPrice);
        }
        
        if(!empty($item->ConcealedOverheadCloser)){
            $result = ironmongeryGetCodeName($item->ConcealedOverheadCloser);
            $ConcealedOverheadCloser = $result['name'];
            $ConcealedOverheadCloserPrice = $result['price'];
            $list = $this->setValue("concealedOverheadCloserKey", $list, $ConcealedOverheadCloser, $ConcealedOverheadCloserPrice);
        }
        
        if(!empty($item->PullHandles)){
            $result = ironmongeryGetCodeName($item->PullHandles);
            $PullHandles = $result['name'];
            $PullHandlesPrice = $result['price'];
            $list = $this->setValue("pullHandlesKey", $list, $PullHandles, $PullHandlesPrice);
        }
        
        if(!empty($item->PushHandles)){
            $result = ironmongeryGetCodeName($item->PushHandles);
            $PushHandles = $result['name'];
            $PushHandlesPrice = $result['price'];
            $list = $this->setValue("pushHandlesKey", $list, $PushHandles, $PushHandlesPrice);
        }
        
        if(!empty($item->KickPlates)){
            $result = ironmongeryGetCodeName($item->KickPlates);
            $KickPlates = $result['name'];
            $KickPlatesPrice = $result['price'];
            $list = $this->setValue("kickPlatesKey", $list, $KickPlates, $KickPlatesPrice);
        }
        
        if(!empty($item->DoorSelectors)){
            $result = ironmongeryGetCodeName($item->DoorSelectors);
            $DoorSelectors = $result['name'];
            $DoorSelectorsPrice = $result['price'];
            $list = $this->setValue("doorSelectorsKey", $list, $DoorSelectors, $DoorSelectorsPrice);
        }
        
        if(!empty($item->PanicHardware)){
            $result = ironmongeryGetCodeName($item->PanicHardware);
            $PanicHardware = $result['name'];
            $PanicHardwarePrice = $result['price'];
            $list = $this->setValue("panicHardwareKey", $list, $PanicHardware, $PanicHardwarePrice);
        }
        
        if(!empty($item->Doorsecurityviewer)){
            $result = ironmongeryGetCodeName($item->Doorsecurityviewer);
            $Doorsecurityviewer = $result['name'];
            $DoorsecurityviewerPrice = $result['price'];
            $list = $this->setValue("doorSecurityViewerKey", $list, $Doorsecurityviewer, $DoorsecurityviewerPrice);
        }
        
        if(!empty($item->Morticeddropdownseals)){
            $result = ironmongeryGetCodeName($item->Morticeddropdownseals);
            $Morticeddropdownseals = $result['name'];
            $MorticeddropdownsealsPrice = $result['price'];
            $list = $this->setValue("morticeddropdownsealsKey", $list, $Morticeddropdownseals, $MorticeddropdownsealsPrice);
        }
        
        if(!empty($item->Facefixeddropseals)){
            $result = ironmongeryGetCodeName($item->Facefixeddropseals);
            $Facefixeddropseals = $result['name'];
            $FacefixeddropsealsPrice = $result['price'];
            $list = $this->setValue("facefixeddropsealsKey", $list, $Facefixeddropseals, $FacefixeddropsealsPrice);
        }
        
        if(!empty($item->ThresholdSeal)){
            $result = ironmongeryGetCodeName($item->ThresholdSeal);
            $ThresholdSeal = $result['name'];
            $ThresholdSealPrice = $result['price'];
            $list = $this->setValue("thresholdSealKey", $list, $ThresholdSeal, $ThresholdSealPrice);
        }
        
        if(!empty($item->AirTransferGrill)){
            $result = ironmongeryGetCodeName($item->AirTransferGrill);
            $AirTransferGrill = $result['name'];
            $AirTransferGrillPrice = $result['price'];
            $list = $this->setValue("airtransfergrillsKey", $list, $AirTransferGrill, $AirTransferGrillPrice);
        }
        
        if(!empty($item->Letterplates)){
            $result = ironmongeryGetCodeName($item->Letterplates);
            $Letterplates = $result['name'];
            $LetterplatesPrice = $result['price'];
            $list = $this->setValue("LetterplatesKey", $list, $Letterplates, $LetterplatesPrice);
        }
        
        if(!empty($item->CableWays)){
            $result = ironmongeryGetCodeName($item->CableWays);
            $CableWays = $result['name'];
            $CableWaysPrice = $result['price'];
            $list = $this->setValue("cableWaysKey", $list, $CableWays, $CableWaysPrice);
        }
        
        if(!empty($item->SafeHinge)){
            $result = ironmongeryGetCodeName($item->SafeHinge);
            $SafeHinge = $result['name'];
            $SafeHingePrice = $result['price'];
            $list = $this->setValue("safeHingeKey", $list, $SafeHinge, $SafeHingePrice);
        }
        
        if(!empty($item->LeverHandle)){
            $result = ironmongeryGetCodeName($item->LeverHandle);
            $LeverHandle = $result['name'];
            $LeverHandlePrice = $result['price'];
            $list = $this->setValue("LeverHandleKey", $list, $LeverHandle, $LeverHandlePrice);
        }
        
        if(!empty($item->DoorSinage)){
            $result = ironmongeryGetCodeName($item->DoorSinage);
            $DoorSinage = $result['name'];
            $DoorSinagePrice = $result['price'];
            $list = $this->setValue("DoorSignageKey", $list, $DoorSinage, $DoorSinagePrice);
        }
        
        if(!empty($item->FaceFixedDoorCloser)){
            $result = ironmongeryGetCodeName($item->FaceFixedDoorCloser);
            $FaceFixedDoorCloser = $result['name'];
            $FaceFixedDoorCloserPrice = $result['price'];
            $list = $this->setValue("FaceFixedDoorClosersKey", $list, $FaceFixedDoorCloser, $FaceFixedDoorCloserPrice);
        }
        
        if(!empty($item->Thumbturn)){
            $result = ironmongeryGetCodeName($item->Thumbturn);
            $Thumbturn = $result['name'];
            $ThumbturnPrice = $result['price'];
            $list = $this->setValue("thumbturnKey", $list, $Thumbturn, $ThumbturnPrice);
        }
        
        if(!empty($item->KeyholeEscutchen)){
            $result = ironmongeryGetCodeName($item->KeyholeEscutchen);
            $KeyholeEscutchen = $result['name'];
            $KeyholeEscutchenPrice = $result['price'];
            $list = $this->setValue("keyholeEscutcheonKey", $list, $KeyholeEscutchen, $KeyholeEscutchenPrice);
        }
        
        if(!empty($item->DoorStops)){
            $result = ironmongeryGetCodeName($item->DoorStops);
            $DoorStops = $result['name'];
            $DoorStopsPrice = $result['price'];
            $list = $this->setValue("DoorStopsKey", $list, $DoorStops, $DoorStopsPrice);
        }
        
        if(!empty($item->Cylinders)){
            $result = ironmongeryGetCodeName($item->Cylinders);
            $Cylinders = $result['name'];
            $CylindersPrice = $result['price'];
            $list = $this->setValue("CylindersKey", $list, $Cylinders, $CylindersPrice);
        }


        return view('Project.addIronmongeryNew',['tooltip' => $tooltip,'pid' => $pid,'item'=> $item,'Hinges' => $Hinges , 'FloorSpring' => $FloorSpring,'LocksAndLatches' => $LocksAndLatches, 'FlushBolts' => $FlushBolts , 'ConcealedOverheadCloser' => $ConcealedOverheadCloser , 'PullHandles' => $PullHandles , 'PushHandles' => $PushHandles , 'KickPlates' => $KickPlates , 'DoorSelectors' => $DoorSelectors , 'PanicHardware' => $PanicHardware , 'Doorsecurityviewer' => $Doorsecurityviewer , 'Morticeddropdownseals' => $Morticeddropdownseals , 'Facefixeddropseals' => $Facefixeddropseals , 'ThresholdSeal' => $ThresholdSeal , 'AirTransferGrill' => $AirTransferGrill , 'Letterplates' => $Letterplates , 'CableWays' => $CableWays , 'SafeHinge' => $SafeHinge , 'LeverHandle' => $LeverHandle , 'DoorSinage' => $DoorSinage , 'FaceFixedDoorCloser' => $FaceFixedDoorCloser , 'Thumbturn' => $Thumbturn , 'KeyholeEscutchen' => $KeyholeEscutchen,
        'HingesPrice' => $HingesPrice,
        'FloorSpringPrice' => $FloorSpringPrice,
        'LocksandLatchesPrice' => $LocksandLatchesPrice,
        'FlushBoltsPrice' => $FlushBoltsPrice,
        'ConcealedOverheadCloserPrice' => $ConcealedOverheadCloserPrice,
        'PullHandlesPrice' => $PullHandlesPrice,
        'PushHandlesPrice' => $PushHandlesPrice,
        'KickPlatesPrice' => $KickPlatesPrice,
        'DoorSelectorsPrice' => $DoorSelectorsPrice,
        'PanicHardwarePrice' => $PanicHardwarePrice,
        'DoorsecurityviewerPrice' => $DoorsecurityviewerPrice,
        'MorticeddropdownsealsPrice' => $MorticeddropdownsealsPrice,

        'FacefixeddropsealsPrice' => $FacefixeddropsealsPrice,
        'ThresholdSealPrice' => $ThresholdSealPrice,
        'AirTransferGrillPrice' => $AirTransferGrillPrice,
        'LetterplatesPrice' => $LetterplatesPrice,
        'CableWaysPrice' => $CableWaysPrice,
        'SafeHingePrice' => $SafeHingePrice,
        'LeverHandlePrice' => $LeverHandlePrice,
        'DoorSinagePrice' => $DoorSinagePrice,
        'FaceFixedDoorCloserPrice' => $FaceFixedDoorCloserPrice,
        'ThumbturnPrice' => $ThumbturnPrice,
        'KeyholeEscutchenPrice' => $KeyholeEscutchenPrice,
        'DoorStops' => $DoorStops,
        'DoorStopsPrice' => $DoorStopsPrice,
        'Cylinders' => $Cylinders,
        'CylindersPrice' => $CylindersPrice,
        'list' => $list,
        ]);

    }

    public function setValue($key, array $list, $name, $price): array
    {
        foreach ($list as $k => $lst) {
            if($lst->key == $key){
                $list[$k]['val_name'] = $name;
                $list[$k]['val_price'] = $price;
                break;
            }
        }
        
        return $list;
    }

    public function delAddIronmongery(Request $request)
    {
        $id = $request->delId;
        AddIronmongery::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Ironmongery deleted successfully!');
    }

    public function deleteproject(Request $request)
    {
        $projectId = $request->projectId;
        $project = Project::where('id',$projectId)->first();
        ProjectInvitation::where('ProjectId',$projectId)->delete();

        $pp = ProjectFiles::select('id','file','tag')->where('projectId',$projectId)->get();
        $filepath = public_path('uploads/Project/');
        foreach($pp as $tt){
            $tag = $tt->tag;
            $projectFileID = $tt->id;
            $filename = $tt->file;
            File::delete($filepath.$filename);
            if($tag == 'DoorSchedule'){
                ProjectFilesDS::where('projectfileId',$projectFileID)->delete();
            }
            
            ProjectFiles::where('id',$projectFileID)->delete();
        }
        
        if($project->ProjectImage != ''){
            File::delete($filepath.$project->ProjectImage);
        }
        
        Quotation::where('ProjectId',$projectId)->update(['ProjectId' => null]);
        AddIronmongery::where('ProjectId',$projectId)->delete();
        Project::where('id',$projectId)->delete();
        return redirect()->back()->with('success', 'Project deleted successfully!');
    }
    
    public function deactivateproject(Request $request)
    {
        $projectId = $request->projectId;
        $project = Project::find($projectId);
        $project->Status = 0;
        $project->save();
        Quotation::where('ProjectId',$projectId)->update(['ProjectId' => null]);
        return redirect()->back()->with('success', 'Project deactivate successfully!');
    }
    
    public function activateproject(Request $request)
    {
        $projectId = $request->projectId;
        $project = Project::find($projectId);
        $project->Status = 1;
        $project->save();
        return redirect()->back()->with('success', 'Project activate successfully!');
    }


    public function assign_projects(request $request){
        return view('Project.AssignedProjects');
    }



    public function getAssignedProjects(Request $request)
    {


        $from = $request->from;
        $limit = $request->limit;

        if($limit == "0" || $limit == ""){
            $limit = PHP_INT_MAX;
        }


        $filters = $request->filters;
        if($request->filters == ""){
            $filters = [];
        }

        for($i = 0 ; $i <= count($filters)-1; $i++){

            $filters[$i] = [$filters[$i][0],$filters[$i][1],$filters[$i][2]];

        }
        
        // $orders = $request->orders;
        // $column = $orders[0]["column"];
        // $dir = $orders[0]["dir"];
        $column = $request->column;
        $dir = $request->dir;


        $loginUserId = Auth::user()->id;
        $login_company_id = get_company_id(Auth::user()->id)->id;
        $UserType = Auth::user()->UserType;


        switch ($UserType){

            case 1:

                if(!empty($request->id)){
                    $filters[] = ['project.UserId', "=", $request->id];
                }

                break;

            case 2:

                if(empty($request->id)){

                    $filters[] = ['project.CompanyId', "=", $login_company_id];
                    $filters[] = ['project.ProjectStatus', "=", 'InvitedToCompany'];


                }else{

                    $filters[] = ['project.UserId', "=", $request->id];
                    $filters[] = ['project.CompanyId', "=", $login_company_id];
                }
                
                break;

            case 4:
                    $filters[] = ['project.ArchitectId', "=", $loginUserId];
                break;

                case 5:
                    $login_contractor_id = get_customer_id(Auth::user()->id)->id;
                    $filters[] = ['project.MainContractorId', "=", $loginUserId];
                break;


            default:
                $filters[] = ['project.UserId', "=", $loginUserId];
        }


        $countProject = Project::leftJoin('companies','companies.id','project.CompanyId')
        ->select('project.*', 'project.id as ProjectId', 'companies.*','quotation.*')


        ->where($filters)->count();



        $data = Project::Join('companies','companies.id','project.CompanyId')
        ->join('quotation','quotation.ProjectId','project.id')
        ->select('project.*', 'project.id as ProjectId', 'companies.*')

        ->where($filters)->skip($from)->take($limit)->orderBy($column, $dir)->get();

        if((array)$data->toArray() !== []){
            $htmlData = '';
            foreach($data as $val)
            {
                $us = User::where('id',$val->editBy)->first();
                $custCompanyName = Customer::where('id',$val->MainContractorId)->first();
                $projectFilesCount = ProjectFiles::where('projectId',$val->ProjectId)->count();
                $CompanyName = $custCompanyName != '' ? $custCompanyName->CstCompanyName : '-----------';

                $quotesCount = $val->quotesCount != '' ? $val->quotesCount : 0;

                $ordersCount = $val->ordersCount != '' ? $val->ordersCount : 0;

                $returnTenderDate = $val->returnTenderDate != '' ? $val->returnTenderDate : '-----------';
                $lastModifier = $us != '' ? $us->FirstName.' '.$us->LastName : '';
                
                //firedoor2_role_update

                $countIronmongerySet = AddIronmongery::where(['ProjectId' => $val->ProjectId])->count();

                if($val->Status == 1){
                    $projectname = '<a href="'.url('project/quotation-list/'.$val->GeneratedKey).'" class="QuotationCode">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="deactivateproject"><i class="fa fa-lock"></i> Deactivate Project</a>';
                } else {
                    $projectname = '<a href="#" class="QuotationCode" style="color: black;">'.$val->ProjectName.'</a>';
                    $activedeactive = '<a href="javascript:void(0);" class="activateproject"><i class="fa fa-unlock-alt"></i> Activate Project</a>';
                }


                // currency showing formate is changed accordingly(dynamically)
                $Currency = '';
                if(!empty($val->projectCurrency)){
                    if ($val->projectCurrency == '£_GBP') {
                        $Currency = "£ GBP";
                    } elseif ($val->projectCurrency == '€_EURO') {
                        $Currency = "€ EURO";
                    } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                        $Currency = "$ US DOLLAR";
                    }
                }
                
                $htmlData .=
                '<div class="col-sm-3 mb-3">
                    <div class="QuotationBox">
                        '.$projectname.'
                        <div class="QuotationCompanyName">
                            <b>'.$CompanyName.'</b>
                        </div>
                        <div class="QuotationStatusNumber">'.$Currency.'</div>
                        <div class="QuotationListData">
                            <b>Project Name</b>
                            <span>'.ucwords($val->ProjectName).'</span>
                            <b>Files</b>
                            <span>'.$projectFilesCount.'</span>
                            <b>Quotes</b>
                            <span>'.$quotesCount.'</span>
                            <b>Orders</b>
                            <span>'.$ordersCount.'</span>
                            <b>Ironmongery Set</b>
                            <span>'.$countIronmongerySet.'</span>
                            <b>Return Tender Date</b>
                            <span>'.date2Formate($returnTenderDate).'</span>
                        </div>
                        <div class="QuotationListNumber"></div>
                        <div class="QuotationModifiedDate">
                            <p>Last modified by '.$lastModifier.' on '.dateFormate($val->Projectupdated_at).'</p>
                        </div>



                    </div>
                </div>';
            }

            // return $htmlData;
            return [
                'st' => "success",
                'txt' => 'data found.',
                'total' => $countProject,
                'html' => $htmlData,
            ];

        } else {
            $htmlData = 'Data not found.';
            return [
                'st' => "error",
                'txt' => 'Data not found.',
                'total' => 0,
                'html' => $htmlData,
            ];


        }

    }

   public function send_mail(request $request){

   }

   public function project_invitation(request $request)
   {
       return view('Mail.SendProjectInvitation');
   }

   public function floorStore(request $request): void{
        $Project = ProjectBuildingDetails::find($request->id);
        if(!empty($Project)){
            if (!empty($request->file('file'))) {
                if($request->hasFile('file')){
                    $file = $request->file('file');
                    $name = time().$file->getClientOriginalName();
                    $filepath = public_path('floorPlan');
                    $file->move($filepath,$name);

                    $pdf_file = public_path()."/floorPlan/".$name;
                    $imageName = $name."image.TIFF";
                    $out_file = public_path()."/floorPlan/".$imageName;
                    $pdf = new Pdf($pdf_file);
                    $pdf->saveImage($out_file);
                    $Project->floorPlan = $imageName;
                }
            } elseif (empty($request->floor_plan)) {
                echo json_encode(["status" => "error", "msg" => "Please select file!"]);
                exit;
            }

            $Project->save();
            echo json_encode(["status" => "success", "msg" => "Floor Plan Updated!"]);
        }else{
            echo json_encode(["status" => "error", "msg" => "Project Id not found!"]);
        }
        
        \Session::flash('status', 'success');
        \Session::flash('message', 'Floor');
    }

    public function floorPlanList($id){
        $surveyId = Crypt::decrypt($id);
        if(empty($surveyId)){
            echo 'something went worng!';
        }else{
            $buildingDetails = ProjectBuildingDetails::Join('survey_info', 'project_building_details.projectId', 'survey_info.projectId')->where('survey_info.id',$surveyId)->select('project_building_details.*')->get();

            $floorPlans = Project::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","project.versionId")
                    ->on("quotation_versions.quotation_id","=","project.quotationId");
            })
            ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
            ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
            ->join('items','items.itemId','item_master.itemID')
            ->Join('survey_info', 'project.id', 'survey_info.projectId')
            ->select('item_master.*','items.*')->where('survey_info.id',$surveyId)->groupBy('item_master.id')->get();
            if($buildingDetails->isNotEmpty()){
                return view('Project.FloorPlan',['buildingDetails' => $buildingDetails, 'floorPlans' => $floorPlans]);
            }else{
                echo 'something went worng!';die;
            }
        }

        return null;
    }

    public function floorDoorList(Request $request): void{

        if($request->floorplan_category_text == ''){
            $floorPlans = Project::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","project.versionId")
                    ->on("quotation_versions.quotation_id","=","project.quotationId");
            })
            ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
            ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
            ->join('items','items.itemId','item_master.itemID')
            ->select('item_master.*','items.*')->where('project.id',$request->projectId)->groupBy('item_master.id')->get();
        }else{
            $floorPlans = Project::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","project.versionId")
                    ->on("quotation_versions.quotation_id","=","project.quotationId");
            })
            ->join('quotation_version_items','quotation_version_items.version_id','quotation_versions.id')
            ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
            ->join('items','items.itemId','item_master.itemID')
            ->select('item_master.*','items.*')->where('project.id',$request->projectId)->where('item_master.floor',$request->floorplan_category_text)->groupBy('item_master.id')->get();
        }

        if (!empty($floorPlans)){
            $content = '';
            foreach ($floorPlans as $plan){
                $content .= '<div class="doorcard">
                <div class="m-2">'.$plan->DoorType.'</div>
                <hr class="my-1">
                <div class="body-info my-3 mx-2">
                    <div class="body-info-title"> Door No</div>
                    <div>
                        <div class="body-info-data ml-1 my-1">'.$plan->doorNumber.'
                        </div>
                    </div>
                </div>
                <div class="body-info my-3 mx-2">
                    <div class="body-info-title"> Fire Rating</div>
                    <div>
                        <div class="body-info-data ml-1">'.$plan->FireRating.'
                        </div>
                    </div>
                </div>
                <div class="body-info my-3 mx-2">
                    <div class="body-info-title">Floor</div>
                    <div>
                        <div class="body-info-data ml-1">'.$plan->floor.'
                        </div>
                    </div>
                </div>
            </div>';
            }
        }else{
            $content = 'Data not found!';
        }

        echo json_encode(["status" => "success", "msg" => "Floor Plan Updated!", "floorPlans" => $content]);
    }

    public function instructionSave(Request $request): void{
        $projectId = $request->projectId;
        if(!empty($projectId) && !empty($request->instruction)){
            $project = Project::find($projectId);
            $project->instruction = $request->instruction;
            $project->save();
            \Session::flash('status', 'success');
            \Session::flash('message', 'Dashboard');
            echo json_encode(["status" => "success", "msg" => "Data Stored Successfully!"]);
        }else{
            echo json_encode(["error" => "error", "msg" => "something went wrong"]);
        }
    }

    public function testMail(Request $request): void{
        $emailTo = 'pankaj@pbinfosystems.com';
        $subject = 'Survey assigned';
        $emailFrom = 'noreply@jfds.co.uk';
        $usermname = 'Kunal';
        $data_set = ['usermname'=>$usermname,'projectName'=>'kunal project', 'fromTime'=>'12', 'toTime'=>'16'];

        ini_set('display_errors', 1);
        try{
            $mail = Mail::send(['html' => 'Mail.Survey'], $data_set, function($message) use(&$emailTo, &$subject, &$emailFrom): void {

                $message->to($emailTo, $emailTo)->subject($subject);
                if($emailFrom !== ''){
                    $message->from($emailFrom, $emailFrom);
                }

            });
            if($mail){
                echo 'dshd';die;
            }else{
                echo 'error';die;
            }

        } catch (Exception $exception) {
                echo $exception->getMessage();
        }
    }

    public function getProjectListExportAll(){
        return Excel::download(new AllProjectExport(), 'project.xlsx');
    }

    public function defaultsStore(Request $request){

        $updateVal = $request->updateVal;
        $data = empty($updateVal) ? new ProjectDefaultItems() : ProjectDefaultItems::find($updateVal);

        $data->projectId = $request->projectId;
        $data->default_type = $request->default_type;
        $data->IntumescentLeafType = 1;
        $data->FireRating = $request->fireRating;
        $data->DoorsetType = $request->doorsetType;
        $data->SwingType = $request->swingType;
        $data->LatchType = $request->latchType;
        $data->Handing = $request->Handing;
        $data->OpensInwards = $request->OpensInwards;
        $data->Tollerance = $request->tollerance;
        $data->FloorFinish = $request->floorFinish;
        $data->GAP = $request->gap;
        $data->FrameThickness = $request->frameThickness;
        $data->DoorLeafFacing = $request->doorLeafFacing;
        $data->DoorLeafFacingValue = $request->doorLeafFacingValue;
        $data->DoorLeafFinish = $request->doorLeafFinish;
        $data->Leaf1VisionPanel = $request->leaf1VisionPanel;
        $data->Leaf1VisionPanelShape = $request->leaf1VisionPanelShape;
        $data->VisionPanelQuantity = $request->visionPanelQuantity;
        $data->AreVPsEqualSizesForLeaf1 = $request->AreVPsEqualSizes;
        $data->DistanceFromtopOfDoor = $request->distanceFromTopOfDoor;
        $data->DistanceFromTheEdgeOfDoor = $request->distanceFromTheEdgeOfDoor;
        $data->DistanceBetweenVPs = $request->distanceBetweenVPs;
        $data->Leaf1VPWidth = $request->vP1Width;
        $data->Leaf1VPHeight1 = $request->vP1Height1;
        $data->Leaf1VPHeight2 = $request->vP1Height2;
        $data->Leaf1VPHeight3 = $request->vP1Height3;
        $data->Leaf1VPHeight4 = $request->vP1Height4;
        $data->Leaf1VPHeight5 = $request->vP1Height5;
        $data->Leaf1VPAreaSizem2 = $request->leaf1VpAreaSizeM2;
        $data->Leaf2VisionPanel = $request->leaf2VisionPanel;
        $data->sVPSameAsLeaf1 = $request->vpSameAsLeaf1;
        $data->Leaf2VisionPanelQuantity = ($request->vpSameAsLeaf1 == "Yes")?$request->visionPanelQuantity:$request->visionPanelQuantityforLeaf2;
        $data->AreVPsEqualSizesForLeaf2 = ($request->vpSameAsLeaf1 == "Yes")?$request->AreVPsEqualSizes:$request->AreVPsEqualSizesForLeaf2;
        $data->DistanceFromTopOfDoorForLeaf2 = $request->distanceFromTopOfDoorforLeaf2;
        $data->DistanceFromTheEdgeOfDoorforLeaf2 = $request->distanceFromTheEdgeOfDoorforLeaf2;
        $data->DistanceBetweenVp = $request->distanceBetweenVPsforLeaf2;
        $data->Leaf2VPWidth = $request->vP2Width;
        $data->Leaf2VPHeight1 = $request->vP2Height1;
        $data->Leaf2VPHeight2 = $request->vP2Height2;
        $data->Leaf2VPHeight3 = $request->vP2Height3;
        $data->Leaf2VPHeight4 = $request->vP2Height4;
        $data->Leaf2VPHeight5 = $request->vP2Height5;
        // $data->GlassIntegrity = $request->lazingIntegrityOrInsulationIntegrity;
        // $data->GlassType = $request->glassType;
        // $data->GlassThickness = $request->glassThickness;
        // $data->GlazingSystems = $request->glazingSystems;
        // $data->GlazingSystemThickness = $request->glazingSystemsThickness;
        // $data->GlazingBeads = $request->glazingBeads;
        // $data->GlazingBeadsThickness = $request->glazingBeadsThickness;
        // $data->glazingBeadsWidth = $request->glazingBeadsWidth;
        // $data->glazingBeadsHeight = $request->glazingBeadsHeight;
        // $data->glazingBeadsFixingDetail = $request->glazingBeadsFixingDetail;
        // $data->GlazingBeadSpecies = $request->glazingBeadSpecies;
        $data->FrameMaterial = $request->frameMaterial;
        $data->FrameType = $request->frameType;
        $data->FrameDepth = $request->frameDepth;
        $data->FrameFinish = $request->frameFinish;
        $data->FrameFinishColor = $request->framefinishColor;
        $data->DoorFrameConstruction = $request->frameCostuction;
        $data->LippingType = $request->lippingType;
        $data->LippingThickness = $request->lippingThickness;
        $data->LippingSpecies = $request->lippingSpecies;
        $data->IntumescentLeapingSealType = $request->intumescentSealType;
        $data->IntumescentLeapingSealLocation = $request->intumescentSealLocation;
        $data->IntumescentLeapingSealColor = $request->intumescentSealColor;
        $data->Architrave = $request->Architrave;
        $data->ArchitraveMaterial = $request->architraveMaterial;
        $data->ArchitraveType = $request->architraveType;
        $data->ArchitraveWidth = $request->architraveWidth;
        $data->ArchitraveHeight = $request->architraveHeight;
        $data->ArchitraveFinish = $request->architraveFinish;
        $data->ArchitraveFinishColor = $request->architraveFinishcolor;
        $data->ArchitraveSetQty = $request->architraveSetQty;
        $data->UserId = Auth::user()->id;
        $data->save();

        return back()->with('success','Updated successfully');
    }
}
