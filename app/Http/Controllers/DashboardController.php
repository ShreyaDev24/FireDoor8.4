<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\DoorSchedule;
use App\Models\Quotation;
use App\Models\Project;
use App\Models\Company;
use App\Models\TeamBoard;
use DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {

        if (Auth::user()->UserType == 2) {
            $myAdminGroup = getMyCreatedAdmins();
            $useTbl = $myAdminGroup;
            $authdata = Auth::user();
            // $useTbl = array_merge(['1'], $myAdminGroup);
            }else{

                // $user = Auth::user();

                // $useTbl = [$user->id];
                $authdata = Auth::user();
            }

        $skip = 0;
        $take = 6;
        $user_count = 0;
        $company_count = 0;
        $quotation_count = 0;
        $customer_count = 0;
        $project_count = 0;
        $order_count = 0;
        $quotation_count_status_wise = 0;
        $recentUsers = 0;
        $recentCompanies = 0;
        $recentCustomers = 0;
        $recentProjects = 0;
        $recentQuotations = 0;
        $recentOrders = 0;
        $RecentUserDetails = '';
        $aboutCompanyDetail = '';
        switch ($authdata->UserType) {

            case 1:
                $user_count = User::where('UserType', 3)->count();
                $company_count = User::join('companies', 'companies.UserId', 'users.id')->where('users.UserType', 2)->count();
                $customer_count = User::join('customers', 'customers.UserId', 'users.id')->where('users.UserType', 5)->count();
                $project_count = Project::count();
                $quotation_count = Quotation::count();

                $quotation_count_status_wise = Quotation::select('QuotationStatus', DB::raw('count(*) as total'))->groupBy('QuotationStatus')->get();


                // Recent Registered Users
                $recentUsers = User::where([
                    ['UserType', '=', '3'],
                ])->skip($skip)->take($take)->orderBy("id", "DESC")->get();
                foreach ($recentUsers as $val) {
                    $image = $val->UserImage;
                    if (!empty($image)) {
                        if (file_exists(public_path('UserImage/' . $image))) {
                            $imagepath = asset('UserImage/' . $image);
                        } else {
                            $imagepath = asset('images/user.jpg');
                        }
                    } else {
                        $imagepath = asset('images/user.jpg');
                    }

                    $company = Company::where('UserId', $val->id)->first();
                    $RecentUserDetails .=
                        '
                        <div class="s-member">
                            <div class="media align-items-center">
                                <a href="' . $imagepath . '"  target="_blank">
                                    <img src="' . $imagepath . '" class="d-block ui-w-30 rounded-circle" alt="user">
                                </a>
                                <div class="media-body ml-5">
                                    <p>
                                        <a href="' . url("user/details/" . $val->id) . '" target="_blank" class="text-dark">' . $val->FirstName . '</a>
                                    </p>
                                    <span>' . $val->CompanyName . '</span>
                                </div>
                                <div class="tm-social">
                                    <a href="tel:+91' . $val->UserPhone . '"><i class="fa fa-phone"></i></a>
                                    <a href="mailto:' . $val->UserEmail . '"><i class="fa fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                        ';
                }


                // Recent Registered Companies
                $aboutCompanies = Company::skip($skip)->take($take)->orderBy("id", "DESC")->get();
                foreach ($aboutCompanies as $val) {
                    $index = 0;
                    $image = $val->UserImage;
                    if (!empty($image)) {
                        if (file_exists(public_path('UserImage/' . $image))) {
                            $imagepath = asset('UserImage/' . $image);
                        } else {
                            $imagepath = asset('images/user.jpg');
                        }
                    } else {
                        $imagepath = asset('images/user.jpg');
                    }

                    $aboutCompanyDetail .=
                        '
                        <tr>
                            <th scope="row">
                                <a href="' . $imagepath . '" target="_blank">
                                    <img width="40px" height="40px" class="rounded" alt="user" src="' . $imagepath . '">
                                </a>
                            </th>
                            <td>
                                <a href="' . url("company/details/" . $val->id) . '" target="_blank" class="text-dark">' . $val->CompanyName . '</a>
                            </td>
                            <td>' . time_elapsed_string($val->created_at) . '</td>
                            <td>' . $val->CompanyEmail . '</td>
                            <td>' . $val->CompanyPhone . '</td>
                        </tr>
                        ';
                }



                // $recentCompanies = User::where('UserType',2)->skip($skip)->take($take)->orderBy("id","DESC")->get();
                $recentCustomers = Customer::join('users', 'users.id', 'customers.UserId')
                    ->select('customers.*', 'users.UserEmail')->where('users.CreatedBy', Auth::user()->id)->orderBy('CstCompanyName', 'asc')->get()->toArray();


                $recentCustomers = Customer::skip($skip)->take($take)->orderBy("id", "DESC")->get();

                // $recentProjects = Project::skip($skip)->take($take)->orderBy("id","DESC")->get();
                $recentProjects = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))->skip($skip)->take($take)->orderBy("project.id", "desc")->get();

                $recentQuotations = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")->leftJoin('companies', 'companies.id', 'quotation.CompanyId')->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')->skip($skip)->take($take)->orderBy("quotation.id", "desc")->get();
                // $recentQuotations= Quotation::skip($skip)->take($take)->orderBy("id","DESC")->get();

                break;

            case 2:


                $user_count = User::whereIn('CreatedBy', $useTbl)->where('UserType', '3')->count();

                $customer_count = User::join('customers', 'customers.UserId', 'users.id')->where('users.UserType', 5)->whereIn('users.CreatedBy', $useTbl)->count();


                // $project_count = Project::where([
                //     ['UserId', '=', $authdata->id],['ProjectStatus','=', 'Accepted']
                // ])->count();

                $project_count = Project::whereIn(
                    'UserId', $useTbl
                )->count();

                $quotation_count = Quotation::whereIn(
                    'UserId', $useTbl
                )->count();


                $quotation_count_status_wise = Quotation::select('QuotationStatus', DB::raw('count(*) as total'))->whereIn(
                   'UserId', $useTbl
                )->groupBy('QuotationStatus')->get();

                $recentUsers = User::where([
                    ['UserType', '=', '3'],
                ])->whereIn('CreatedBy',$useTbl)->skip($skip)->take($take)->orderBy("id", "DESC")->get();

                $recentCustomers = Customer::join('users', 'users.id', 'customers.UserId')
                    ->select('customers.*', 'users.UserEmail')->whereIn('users.CreatedBy', $useTbl)->orderBy('CstCompanyName', 'asc')->get()->toArray();;


                $recentProjects = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')
                    ->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
                    ->whereIn(
                        'project.UserId',  $useTbl
                    )->skip($skip)->take($take)->orderBy("project.id", "desc")->get();

                $recentQuotations = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->whereIn('quotation.UserId', $useTbl)->skip($skip)->take($take)->orderBy("quotation.id", "desc")->get();



                break;

            case 3:
                $users = User::where('UserType', 3)->where('id', Auth::user()->id)->first();
                $UserId = [Auth::user()->id, intval($users->CreatedBy)];

                $customer_count = User::join('customers', 'customers.UserId', 'users.id')->where('users.UserType', 5)->wherein('users.CreatedBy', $UserId)->count();


                $project_count = Project::wherein('project.UserId', $UserId)->count();

                // $project_count = Project::where([
                //     ['project.UserId', '=', $authdata->id],['ProjectStatus','=', 'Accepted']
                // ])->count();


                $quotation_count = Quotation::where([
                    ['UserId', '=', $authdata->id]
                ])->count();

                $quotation_count_status_wise = Quotation::select('QuotationStatus', DB::raw('count(*) as total'))->where([
                    ['UserId', '=', $authdata->id]
                ])->groupBy('QuotationStatus')->get();


                $recentCustomers = Customer::join('users', 'users.id', 'customers.UserId')
                    ->select('customers.*', 'users.UserEmail')->where('users.CreatedBy', Auth::user()->id)->orderBy('CstCompanyName', 'asc')->get()->toArray();



                $recentProjects = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')
                    ->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
                    ->where([
                        ['project.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("project.id", "desc")->get();

                $recentQuotations = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->where([
                        ['quotation.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("quotation.id", "desc")->get();

                break;

            case 4:

                // $customer_count = Customer::where([
                //     ['UserId', '=', $authdata->id]
                // ])->count();

                $customer_count = User::join('customers', 'customers.UserId', 'users.id')->where('users.UserType', 5)->where('users.CreatedBy', $authdata->id)->count();

                $project_count = Project::where([
                    ['UserId', '=', $authdata->id]
                ])->count();

                $quotation_count = Quotation::where([
                    ['UserId', '=', $authdata->id]
                ])->count();


                $quotation_count_status_wise = Quotation::select('QuotationStatus', DB::raw('count(*) as total'))->where([
                    ['UserId', '=', $authdata->id]
                ])->groupBy('QuotationStatus')->get();



                $recentCustomers = Customer::join('users', 'users.id', 'customers.UserId')
                    ->select('customers.*', 'users.UserEmail')->where('users.CreatedBy', Auth::user()->id)->orderBy('CstCompanyName', 'asc')->get()->toArray();;


                $recentProjects = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')
                    ->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
                    ->where([
                        ['project.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("project.id", "desc")->get();

                $recentQuotations = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->where([
                        ['quotation.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("quotation.id", "desc")->get();

                break;

            case 5:

                $customer_count = Customer::where([
                    ['UserId', '=', $authdata->id]
                ])->count();


                $project_count = Project::where([
                    ['UserId', '=', $authdata->id]
                ])->count();

                $quotation_count = Quotation::where([
                    ['UserId', '=', $authdata->id]
                ])->count();


                $quotation_count_status_wise = Quotation::select('QuotationStatus', DB::raw('count(*) as total'))->where([
                    ['UserId', '=', $authdata->id]
                ])->groupBy('QuotationStatus')->get();



                $recentCustomers = Customer::where([
                    ['UserId', '=', $authdata->id]
                ])->skip($skip)->take($take)->orderBy("id", "DESC")->get();


                $recentProjects = Project::leftJoin('companies', 'companies.id', 'project.CompanyId')
                    ->select('project.*', 'project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
                    ->where([
                        ['project.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("project.id", "desc")->get();

                $recentQuotations = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->where([
                        ['quotation.UserId', '=', $authdata->id]
                    ])->skip($skip)->take($take)->orderBy("quotation.id", "desc")->get();

                break;

            default:
        }



        return view('Dashboard.Index', compact(
            'authdata',
            'customer_count',
            'quotation_count',
            'user_count',
            'project_count',
            'order_count',
            'company_count',
            'quotation_count_status_wise',
            'recentUsers',
            'recentCompanies',
            'recentCustomers',
            'recentProjects',
            'recentQuotations',
            'recentQuotations',
            'recentOrders',
            'RecentUserDetails',
            'aboutCompanyDetail'
        ));
    }

    public function notificationShow()
    {

        $myCreatedUser = myCreatedUser();
        $myCreatedUser = array_diff($myCreatedUser, [Auth::user()->id]);
        $u_type = Auth::user()->UserType;

        $uId = Auth::user()->id;
        $notifications = [];

        // GET PROJECT DATA
        $projects = Project::whereIn('project.UserId', $myCreatedUser)->whereRaw("NOT find_in_set($uId, project.read_by)")->join('users as u', 'u.id', '=', 'project.UserId')->select('project.*', 'u.FirstName', 'u.UserImage', 'u.UserType', DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'project' ELSE 'project' END) as notificationType"))->get()->toArray();
        $notifications = array_merge($notifications, $projects);


        // GET QUOTE DATA
        $quotes = Quotation::wherein('quotation.UserId',$myCreatedUser)->leftJoin("quotation_versions",function($join){
            $join->on("quotation.id","quotation_versions.quotation_id")
            ->orOn("quotation_versions.id","=","quotation.VersionId");
        })->whereRaw("NOT find_in_set($uId, quotation.read_by)")->join('users as u', 'u.id','=','quotation.UserId')->select('quotation.*', 'u.FirstName', 'u.UserImage', 'u.UserType','quotation_versions.id as QVID',  DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'quote' ELSE 'quote' END) as notificationType"))->groupBy('quotation.id')->get()->toArray();
        $notifications = array_merge($notifications, $quotes);


        // GET CONTRACTOR DATA
        $contractor = Customer::wherein('uc.CreatedBy',$myCreatedUser)->where(['uc.UserType'=> 5])->whereRaw("NOT find_in_set($uId, customers.read_by)")->join('users as uc', 'uc.id','=','customers.UserId')->join('users as u', 'u.id','=','uc.CreatedBy')->select('customers.*', 'uc.FirstName as ContractorName', 'u.FirstName', 'u.UserImage', 'u.UserType',  DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'contractor' ELSE 'contractor' END) as notificationType"))->get()->toArray();
        $notifications = array_merge($notifications, $contractor);

        if($u_type != 3){
            // GET USER DATA
            $users = User::wherein('users.CreatedBy',$myCreatedUser)->wherein('users.UserType',[2,3])->whereRaw("NOT find_in_set($uId, users.read_by)")->join('users as u', 'u.id','=','users.CreatedBy')->select('users.id','users.FirstName as childName', 'users.UserType as childType','users.UserJobtitle','users.created_at', 'u.FirstName', 'u.UserImage', 'u.UserType',  DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'user' ELSE 'user' END) as notificationType"))->get()->toArray();
            $notifications = array_merge($notifications, $users);
        }


        // GET TEAM BOARD DATA
        $teamBoardMsg = TeamBoard::wherein('team_boards.UserId',$myCreatedUser)->whereRaw("(SELECT COUNT(tb.id) FROM team_boards tb WHERE tb.ProjectId=team_boards.ProjectId AND tb.UserId != ".auth()->user()->id." AND (SELECT COUNT(id) FROM notification_read nr WHERE nr.type='team_board_msg' AND nr.typeId=tb.id AND nr.UserId='".auth()->user()->id."' AND tb.UserId != ".auth()->user()->id.")=0) != 0")
        ->join('users as u', 'u.id','=','team_boards.UserId')
        ->leftjoin('project as p', 'p.id','=','team_boards.ProjectId')
        ->select('p.ProjectName','p.GeneratedKey', 'team_boards.*', 'u.FirstName', 'u.UserImage', 'u.UserType',DB::raw("( SELECT COUNT(tb.id) FROM team_boards tb WHERE tb.ProjectId=team_boards.ProjectId AND tb.UserId != ".auth()->user()->id." AND (SELECT COUNT(id) FROM notification_read nr WHERE nr.type='team_board_msg' AND nr.typeId=tb.id AND nr.UserId='".auth()->user()->id."'  AND nr.UserId='".auth()->user()->id."')=0) as unreadCount") , DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'teamBoard' ELSE 'teamBoard' END) as notificationType"))->groupBy('team_boards.ProjectId')->orderBy('team_boards.id','DESC')->get()->toArray();
        $notifications = array_merge($notifications, $teamBoardMsg);


        // GET QUOTE ACCEPT/REJECT DATA
        $quotesStats = Quotation::wherein('quotation.UserId',$myCreatedUser)->wherein('quotation.QuotationStatus', ['Accept', 'Reject'])->leftJoin("quotation_versions",function($join){
            $join->on("quotation.id","quotation_versions.quotation_id")
            ->orOn("quotation_versions.id","=","quotation.VersionId");
        })->whereRaw("NOT find_in_set($uId, quotation.status_read_by)")->join('users as u', 'u.id','=','quotation.UserId')->select('quotation.*', 'u.FirstName', 'u.UserImage', 'u.UserType','quotation_versions.id as QVID',  DB::raw("(CASE WHEN u.id IS NOT NULL THEN 'quoteStatus' ELSE 'quoteStatus' END) as notificationType"))->groupBy('quotation.id')->get()->toArray();
        $notifications = array_merge($notifications, $quotesStats);


        // SORT NOTIFICATION BY CREATED_AT
        usort($notifications, function ($a, $b) {
            $dateA = strtotime($a['created_at']);
            $dateB = strtotime($b['created_at']);
            return $dateB - $dateA;
        });

        $html =  view('Notification.showNotification', compact('notifications'))->render();

        return $html;
    }

    public function notificationMarkRead(Request $request) {
        markAsRead($request->p_id, 'teamBoardMsg');
        print_r(['succes' => true]); die;
    }
}
