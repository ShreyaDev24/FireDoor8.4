<?php

namespace App\Http\Controllers\order;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use View;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\Project;
use App\Models\Company;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\ShippingAddress;
use App\Models\Items;
use App\Models\NonConfigurableItems;
use App\Models\ConfigurableItems;
use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFfooter;
use App\Models\SettingPDFDocument;
use App\Models\QuotationContactInformation;
use App\Models\QuotationShipToInformation;
use App\Models\QuotationSiteDeliveryAddress;
use App\Models\Tooltip;
use App\Models\AddIronmongery;
use App\Models\SettingCurrency;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllOrderExport;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orderlist()
    {
        $LoginUserId = Auth::user()->id;
        return view('Order.orderlist',['LoginUserId' => $LoginUserId]);
    }

    public function suborderlist(Request $request): void
    {
        //dd($request->all());
        if ($request->input('isStatus') == 11) {
            $orderData = json_decode((string) $request->input('orders'), true);
            $request->merge(['orders' => $orderData]);
        }

        if($request->ajaxCall == 1){
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

            $orders = $request->orders;
            $column = $orders[0]["column"];
            $dir = $orders[0]["dir"];
        }

        $loginUserId = Auth::user()->id;
        $loginCompanyId = Auth::user()->CompanyId;
        $UserType = Auth::user()->UserType;

        switch ($UserType){
            case 1:
                if(!empty($request->id)){
                    $filters[] = ['quotation.UserId', "=", $request->id];
                }

                $filters[] = ['quotation.CompanyId', "!=", 1];
                break;
            case 2:
                $login_company_id = get_company_id(Auth::user()->id)->id;
                if(empty($request->id)){
                    $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                }else{
                    $filters[] = ['quotation.UserId', "=", $request->id];
                    $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                }

                break;
            case 3:
                $login_company_id = get_company_id(Auth::user()->CreatedBy)->id;
                if(empty($request->id)){
                    $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                }else{
                    $filters[] = ['quotation.UserId', "=", $request->id];
                    $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                }

                break;
            default:
            $filters[] = ['quotation.UserId', "=", $loginUserId];
        }

        if($UserType == 1){
            $Quotations = Quotation::join("quotation_versions",function($join): void{
                    $join->on("quotation_versions.id","=","quotation.VersionId")
                        ->on("quotation.id","quotation_versions.quotation_id");
                })
                ->leftJoin("project","project.id","quotation.ProjectId")
                ->leftJoin('companies','companies.id','quotation.CompanyId')
                ->select('quotation.*', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*')
                ->where('quotation.QuotationStatus','=','Ordered')
                ->where($filters)
                ->where(function($query) use ($filters): void {
                    $query->orWhere($filters)
                    ->orWhere('quotation.QuotationName','LIKE',$filters[0][2])
                    ->orWhere('quotation.FollowUpDate','LIKE',$filters[0][2])
                    ->orWhere('quotation.PONumber','LIKE',$filters[0][2])
                    ->orWhere('project.ProjectName','LIKE',$filters[0][2]);
                });
                //->get();
                if($request->listType=='dataListType'){
                    $Quotations = $Quotations->orderBy($column, $dir)->get();
                }else{

                    $Quotations = $Quotations->skip($from)->take($limit)->orderBy($column, $dir)->get();
                }


            $QuotationsCount = Quotation::join("quotation_versions",function($join): void{
                    $join->on("quotation_versions.id","=","quotation.VersionId")
                        ->on("quotation.id","quotation_versions.quotation_id");
                })
                ->leftJoin("project","project.id","quotation.ProjectId")
                ->leftJoin('companies','companies.id','quotation.CompanyId')
                ->select('quotation.*', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*')
                ->where('quotation.QuotationStatus','=','Ordered')
                ->where($filters)
                ->count();
        } else {

            $Quotations = Quotation::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","quotation.VersionId")
                    ->on("quotation.id","quotation_versions.quotation_id");
            })
            ->leftJoin("project","project.id","quotation.ProjectId")
            ->leftJoin('companies','companies.id','quotation.CompanyId')
            ->leftJoin('customers','customers.id','quotation.CustomerId')
            ->select('quotation.*', 'quotation.editBy as QuotEditBy','quotation.updated_at as QuotUpdatedAt','quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*','quotation_versions.id as QVID','customers.CstCompanyName')
            ->where('quotation.QuotationStatus','=','Ordered')
            ->where(function($query) use ($filters): void {
                $query->orWhere($filters)
                ->orWhere('quotation.QuotationName','LIKE',$filters[0][2])
                ->orWhere('quotation.FollowUpDate','LIKE',$filters[0][2])
                ->orWhere('quotation.PONumber','LIKE',$filters[0][2])
                ->orWhere('project.ProjectName','LIKE',$filters[0][2])
                ->orWhere('customers.CstCompanyName','LIKE',$filters[0][2]);
            });

            // ->skip($from)
            // ->take($limit)
            // ->orderBy("$column", "$dir")
            // ->get();

            if($request->listType=='dataListType'){
                $Quotations = $Quotations->orderBy($column, $dir)->get();
            }else{

                $Quotations = $Quotations->skip($from)->take($limit)->orderBy($column, $dir)->get();
            }


            $QuotationsCount = Quotation::join("quotation_versions",function($join): void{
                $join->on("quotation_versions.id","=","quotation.VersionId")
                    ->on("quotation.id","quotation_versions.quotation_id");
                })
            ->leftJoin("project","project.id","quotation.ProjectId")
            ->leftJoin('companies','companies.id','quotation.CompanyId')
            ->select('quotation.*', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*')
            ->where('quotation.QuotationStatus','=','Ordered')
            ->where($filters)

            ->count();
        }

        if($Quotations !== null){
            $htmlData = '';

            if ($request->input('listType') == 'dataListType') {
                // $Quotations = $Quotations->toArray();
                $htmlData .= '<table id="dataListType" class="table table-hover table-striped table-bordered dataTable no-footer dtr-inline">
               <thead class="text-uppercase table-header-bg text-white">
                   <tr>
                       <th>S.N</th>
                       <th>Quotation Id</th>
                       <th>Quotation Company Name</th>
                       <th>Price</th>
                       <th>Quotation Name</th>
                       <th>Project</th>
                       <th>Due Date</th>
                       <th>Number of Door Sets</th>
                       <th>P.O. Number</th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody>';
                $sn = 1;
           foreach($Quotations as $val){

            if($val['QuotationStatus'] != ''){
                if ($val['QuotationStatus'] == 'Open') {
                    $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">'.$val['QuotationStatus'].'</strong>';
                } elseif ($val['QuotationStatus'] == 'Ordered' || $val['QuotationStatus'] == 'Accept') {
                    $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">'.$val['QuotationStatus'].'</strong>';
                } elseif ($val['QuotationStatus'] == 'All') {
                    $quotation_status = '<strong class="QuotationStatus" style="background:#808080;">'.$val['QuotationStatus'].'</strong>';
                } else {
                    $quotation_status = '<strong class="QuotationStatus" style="background:red;">'.$val['QuotationStatus'].'</strong>';
                }
            } else {
                $quotation_status = null;
            }

            $version = $val['version'] != ""?$val['version']:1;
            $QVID = $val['QVID'] != ""?$val['QVID']:0;
            $bomTag = $val['bomTag'] != ""?$val['bomTag']:0;

            $CstCompanyName = $val['CstCompanyName'] != '' ? $val['CstCompanyName'] : '-----------';

            $QuotationName = $val['QuotationName'] != '' ? $val['QuotationName'] : '-----------';

            $ProjectName = $val['ProjectName'] != '' ? $val['ProjectName'] : '-----------';

            $ExpiryDate = $val['ExpiryDate'] != '' ? $val['ExpiryDate'] : '-----------';

            // if($version > 0){
                $NumberOfDoorSets = NumberOfDoorSets($QVID,$val['QuotationId']);
            // }

            $PONumber = $val['PONumber'] != '' ? $val['PONumber'] : '-----------';

            $ProjectId = $val['ProjectId'] != '' ? $val['ProjectId'] : 0;

            if($val['QuotEditBy'] != ''){
                $us = User::where('id',$val['QuotEditBy'])->first();
                $lastModifyName = $us['FirstName'] != '' ? $us['FirstName'].' '.$us['LastName'] : '-----------';
            } else {
                $lastModifyName = '-----------';
            }

            $DoorsetPrice = 0;
            $Item = Item::join('item_master','item_master.itemID','=','items.itemId')->join('quotation','quotation.id','=','items.QuotationId')->
            join("quotation_version_items",function($join): void{
                $join->on("quotation_version_items.itemID","=","items.itemId")
                    ->on("quotation_version_items.itemmasterID","=","item_master.id");
            })->where('quotation.QuotationGenerationId',$val->QuotationGenerationId)->where('quotation_version_items.version_id',$QVID)->where('items.VersionId',$QVID)->get();
            if(!empty($Item)){
                foreach($Item as $value){
                    $DoorsetPrice += (($value->AdjustPrice)?floatval($value->AdjustPrice) :floatval($value->DoorsetPrice)) + $value->IronmongaryPrice;
                }
            }

            $discountPrice = ($DoorsetPrice + nonConfigurableItem($val->QuotationId,$QVID,CompanyUsers(),'',true)) * $val->QuoteSummaryDiscount/100;
            $DoorsetPrice = ($DoorsetPrice + nonConfigurableItem($val->QuotationId,$QVID,CompanyUsers(),'',true)) - $discountPrice;
            if(!empty($val->projectCurrency)){
                if ($val->projectCurrency == '£_GBP') {
                    $Currency = "£";
                } elseif ($val->projectCurrency == '€_EURO') {
                    $Currency = "€";
                } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                    $Currency = "$";
                }
            }else{
                $Currency = "£";
            }


            $htmlData .= '<tr>
                <td>'.$sn.'</td>
                <td> <a href="'.url('order/generate/'.$val['QuotationId']).'" class="QuotationCode">'.$val['OrderNumber'].'</a></td>
                <td><b>'.$CstCompanyName .  $quotation_status.'</b></td>
                <td>'.$Currency.floatval($DoorsetPrice).'</td>
                <td>'.$QuotationName.'</td>
                <td>'.$ProjectName.'</td>
                <td>'.date2Formate($ExpiryDate).'</td>
                <td>'.$NumberOfDoorSets.'</td>
                <td>'.$PONumber.'</td>
                <td><div class="dropdown">
                <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ....
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item"  href="'.url('quotation/generate/'.$val['QuotationId']).'/'.$QVID.'" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                  <a class="dropdown-item" href="javascript:void(0);" onClick="OMmanualQuotation('.$val['QuotationId'].','.$QVID.','.$ProjectId.');"><i class="fa fa-book"></i> O&M Manual</a>
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
            foreach($Quotations as $val){
                if($val['QuotationStatus'] != ''){
                    if ($val['QuotationStatus'] == 'Open') {
                        $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">'.$val['QuotationStatus'].'</strong>';
                    } elseif ($val['QuotationStatus'] == 'Ordered' || $val['QuotationStatus'] == 'Accept') {
                        $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">'.$val['QuotationStatus'].'</strong>';
                    } elseif ($val['QuotationStatus'] == 'All') {
                        $quotation_status = '<strong class="QuotationStatus" style="background:#808080;">'.$val['QuotationStatus'].'</strong>';
                    } else {
                        $quotation_status = '<strong class="QuotationStatus" style="background:red;">'.$val['QuotationStatus'].'</strong>';
                    }
                } else {
                    $quotation_status = null;
                }

                $version = $val['version'] != ""?$val['version']:1;
                $QVID = $val['QVID'] != ""?$val['QVID']:0;
                $bomTag = $val['bomTag'] != ""?$val['bomTag']:0;

                $CstCompanyName = $val['CstCompanyName'] != '' ? $val['CstCompanyName'] : '-----------';

                $QuotationName = $val['QuotationName'] != '' ? $val['QuotationName'] : '-----------';

                $ProjectName = $val['ProjectName'] != '' ? $val['ProjectName'] : '-----------';

                $ExpiryDate = $val['ExpiryDate'] != '' ? $val['ExpiryDate'] : '-----------';

                // if($version > 0){
                    $NumberOfDoorSets = NumberOfDoorSets($QVID,$val['QuotationId']);
                // }

                $PONumber = $val['PONumber'] != '' ? $val['PONumber'] : '-----------';

                $ProjectId = $val['ProjectId'] != '' ? $val['ProjectId'] : 0;

                if($val['QuotEditBy'] != ''){
                    $us = User::where('id',$val['QuotEditBy'])->first();
                    $lastModifyName = $us['FirstName'] != '' ? $us['FirstName'].' '.$us['LastName'] : '-----------';
                } else {
                    $lastModifyName = '-----------';
                }

                $DoorsetPrice = 0;
                $Item = Item::join('item_master','item_master.itemID','=','items.itemId')->join('quotation','quotation.id','=','items.QuotationId')->
                join("quotation_version_items",function($join): void{
                    $join->on("quotation_version_items.itemID","=","items.itemId")
                        ->on("quotation_version_items.itemmasterID","=","item_master.id");
                })->where('quotation.QuotationGenerationId',$val->QuotationGenerationId)->where('quotation_version_items.version_id',$QVID)->where('items.VersionId',$QVID)->get();
                if(!empty($Item)){
                    foreach($Item as $value){
                        $DoorsetPrice += (($value->AdjustPrice)?floatval($value->AdjustPrice) :floatval($value->DoorsetPrice)) + $value->IronmongaryPrice;
                    }
                }

                $discountPrice = ($DoorsetPrice + nonConfigurableItem($val->QuotationId,$QVID,CompanyUsers(),'',true)) * $val->QuoteSummaryDiscount/100;
                $DoorsetPrice = ($DoorsetPrice + nonConfigurableItem($val->QuotationId,$QVID,CompanyUsers(),'',true)) - $discountPrice;
                if(!empty($val->projectCurrency)){
                    if ($val->projectCurrency == '£_GBP') {
                        $Currency = "£";
                    } elseif ($val->projectCurrency == '€_EURO') {
                        $Currency = "€";
                    } elseif ($val->projectCurrency == '$_US_DOLLAR') {
                        $Currency = "$";
                    }
                }else{
                    $Currency = "£";
                }

                $htmlData .=
                '
                <div class="col-sm-3 mb-3">
                    <div class="QuotationBox">
                        <a href="'.url('order/generate/'.$val['QuotationId']).'" class="QuotationCode">'.$val['OrderNumber'].'</a>
                        <div class="QuotationCompanyName">
                            <b>'.$CstCompanyName .  $quotation_status.'</b>
                        </div>
                        <div class="QuotationStatusNumber">'.$Currency.floatval($DoorsetPrice).'</div>
                        <div class="QuotationListData">
                            <b>Quotation Name</b>
                            <span>'.$QuotationName.'</span>
                            <b>Project</b>
                            <span>'.$ProjectName.'</span>
                            <b>Due Date</b>
                            <span>'.date2Formate($ExpiryDate).'</span>
                            <b>Number of Door Sets</b>
                            <span>'.$NumberOfDoorSets.'</span>
                        </div>
                        <div class="QuotationListNumber">
                            <b>P.O. Number</b>
                            <span>'.$PONumber.'</span>
                        </div>
                        <div class="QuotationModifiedDate">
                            <p>Last modified by '.$lastModifyName.' on '.dateFormate($val['QuotUpdatedAt']).'</p>
                        </div>
                        <div class="filter_action">
                            <label for="filter" class="quote_filter">
                                <i class="fas fa-ellipsis-h"></i>
                            </label>
                            <ul class="QuotationMenu">
                                <li>
                                    <a href="'.url('quotation/generate/'.$val['QuotationId']).'/'.$QVID.'" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" onClick="OMmanualQuotation('.$val['QuotationId'].','.$QVID.','.$ProjectId.');"><i class="fa fa-book"></i> O&M Manual</a>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>';
            }
        }

            // $Quotations = $Quotations->toArray();

            if(!empty($Quotations)){

                // $htmlData = View::make('Order.Ajax.Ajaxorderlist',compact('Quotations'))->render();

                ms([
                    'st' => "success",
                    'txt' => 'Data found.',
                    'total' => $QuotationsCount,
                    'html' => $htmlData,

                ]);
            }else{
                ms([
                    'st' => "error",
                    'txt' => 'Data not found.',
                    'total' => 0,
                    'html' => "",
                ]);
            }

        }else{
            ms([
                'st' => "error",
                'txt' => 'Data not found.',
                'total' => 0,
                'html' => "",
            ]);

        }

    }

    public function OrderDetails(string $Id,$vId,$pId=null,$cId=null){
        // return (explode("/",$_SERVER['REQUEST_URI']));
        if($Id == 0 && $vId == 0){
            $qidFromhelper = GenerateQuotationFirstTime($pId,$cId);
            return redirect()->route('quotation/generate/',[$qidFromhelper,0]);
        } else {
            $Quotation = Quotation::where('id',$Id)->first();
            $vId = $Quotation->VersionId;
            $QuotationContactInformation = QuotationContactInformation::where('QuotationId',$Id)->first();
            $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId',$Id)->first();
        }

        if($Quotation === null){
            return abort(404);
        }

        // $ProjectTable = '<option value="">Select Project</option>';

        if($Quotation->CustomerId != ''){
            $dd = Project::where(['CompanyId' => $Quotation->CompanyId , 'customerId' => $Quotation->CustomerId])->count();
            if($dd > 0){
                if($Quotation->ProjectId != ''){
                    // it show single project it come from project list when create New Quotation press button `New Quotation`
                    $Projects = Project::where(['CompanyId' => $Quotation->CompanyId , 'id' => $Quotation->ProjectId , 'Status' => 1])->get();
                } else {
                    // when you directly create quotation it show multiple project
                    $Projects = Project::where(['CompanyId' => $Quotation->CompanyId , 'customerId' => $Quotation->CustomerId , 'Status' => 1])->get();
                }

            } else {
                $Projects = Project::where(['CompanyId'=>$Quotation->CompanyId , 'Status' => 1])->get();
            }
        } else {
            $Projects = Project::where(['CompanyId'=>$Quotation->CompanyId , 'Status' => 1])->get();
        }



        if(!empty($Quotation))
        {

            if($vId > 0){
                $Schedule = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                ->where('quotation_version_items.version_id',$vId)->get();

                // Total Door Price
                $TotalDoorPrice = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                ->where(['quotation_version_items.version_id'=>$vId,'items.VersionId'=>$vId,'items.QuotationId' => $Id]);

                $TotalExactDoorPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = $TotalDoorPrice->sum('items.IronmongaryPrice');
            } else {
                $Schedule = Item::join('item_master','items.itemId','item_master.itemID')
                ->where(['items.QuotationId' => $Id ])->get();

                // Total Door Price
                $TotalDoorPrice = Item::join('item_master','items.itemId','item_master.itemID')
                    ->where(['items.QuotationId' => $Id ]);

                // $TotalDoorSetPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = $TotalDoorPrice->sum('items.IronmongaryPrice');
            }

            $TotalDoorSetPrice = itemAdjustCount($Id,$vId);
            $nonConfigData = nonConfigurableItem($Id,$vId,CompanyUsers());
            $nonConfigDataPrice = nonConfigurableItem($Id,$vId,CompanyUsers(),'',true);
            $total_price = $TotalDoorSetPrice +  $TotalIronmongeryPrice + $nonConfigDataPrice;

            $Version = QuotationVersion::where('quotation_id',$Id)->get()->toArray();
            $MaxVersion = QuotationVersion::where('quotation_id',$Id)->max('version');

            $companykacustomer = "";
            $customerMultiContact = "";
            if(Auth::user()->UserType == "2"){
                $Quotation;
                $UserId = Auth::user()->id;
                $companykacustomer = Customer::join('users', 'customers.UserId' ,'=','users.id')->where(['users.CreatedBy' => $UserId ])->orderBy('customers.id','desc')->get();

                $customerMultiContact = CustomerContact::join('customers','customers.id','=','customer_contacts.MainContractorId')->select('customer_contacts.*')->where(['customers.UserId' => $Quotation->MainContractorId])->get();


            }else{
                $companykacustomer = Customer::where(['UserId' => Auth::user()->id ])->orderBy('customers.id','desc')->get();
                $customerMultiContact = CustomerContact::where(['MainContractorId' => $Quotation->MainContractorId])->get();


            }

            $CustomerDetails = CustomerContact::join('customers','customers.id','=','customer_contacts.MainContractorId')
                ->where('customers.UserId',$Quotation->MainContractorId)->first();

            $nonconfigdata = NonConfigurableItems::wherein('userId',CompanyUsers())->orderBy('id','desc')->get();
            $NonConfig = '<div class="col-sm-12 p-0">
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-header-bg">
                        <tr class="text-white">
                            <th>Line</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Product Code</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="versionData">';
                    $SI = 1;
            foreach($nonconfigdata as $value){
                // $NonConfig .=
                // '
                // <div class="col-sm-6 p-0 pr-1">
                //     <div class="Quote_tems">
                //         <img src="'.$nonconfigdatas->NonconfiBase64.'">
                //         <a href="javascript:void(0);">'.$nonconfigdatas->name.'</a>
                //         <p class="description_msg">
                //             '.$nonconfigdatas->description.'
                //         </p>
                //         <p class="nonConfigData">Product Code : <span>'.$nonconfigdatas->product_code.'</span></p>
                //         <p class="nonConfigData">Unit : <span>'.$nonconfigdatas->unit.'</span></p>
                //         <p class="nonConfigData">Price : <span>$'.$nonconfigdatas->price.'</span></p>
                //         <input type="number" class="form-control " placeholder="Quantity" style="display:inline-block !important; max-width: 120px;font-size: 14px !important;" id="nonconfigQuantity-'.$nonconfigdatas->id.'" value="">
                //         <a href="javascript:void(0);" data-type="strebord" onclick="nonConfigStore('.$Id.','.$vId.','.$nonconfigdatas->id.','.$nonconfigdatas->price.');" class="configure_btn">Non Configure</a>
                //     </div>
                // </div>
                // ';
                $NonConfig .= '<tr>
                <td>'.$SI++ .'</td>
                <td>'.$value->name .'</td>
                <td><img src="'.$value->NonconfiBase64 .'" alt="Non-ConfigImage" style="width: 100px;"></td>
                <td>'.$value->product_code .'</td>
                <td><p style="max-width: 200px;"><script type="text/javascript">
                         document.write(ReadMore(5,"'.$value->description.'"))
                     </script></p></td>
                <td>'.$value->unit .'</td>
                <td>'.floatval($value->price) .'</td>
                <td><input type="number" class="form-control " placeholder="Quantity" style="margin: 0 auto; max-width: 50px;font-size: 14px !important;" id="nonconfigQuantity-'.$value->id.'" value=""></td>
                <td><a href="javascript:void(0);" data-type="strebord" onclick="nonConfigStore('.$Id.','.$vId.','.$value->id.','.$value->price.');" class="configure_btn">Add</a></td>
            </tr>';
            }

            $NonConfig .= '</tbody></table></div></div></div>';

            // hide or disabled 'Add Item' button from GenerateQuotation.blade page
            // Button only appear when version is selected.
            $selectQV = ['selectVersionID'=>0,'selectVersion'=>0];
            $additem = 0;
            if($vId > 0){
                $QV = QuotationVersion::where('id',$vId)->first();
                $selectQV = ['selectVersionID'=>$QV->id,'selectVersion'=>$QV->version];
            }

            // Configurable Items
            $configurableItem = ConfigurableItems::get();
            $configItem = '';
            foreach($configurableItem as $ci){
                if(!empty($Quotation->configurableitems)){
                    if($Quotation->configurableitems == $ci->id){
                        $btnLink =
                        '
                            <a href="javascript:void(0);" data-type="'.$ci->id.'"
                            class="configure_btn">Create <br>Door Type</a>
                            <a href="javascript:void(0);"
                            data-type="'.$ci->id.'" class="configure_btn configure_door_btn">Add
                            Additional <br> Door Type</a>
                        ';
                    } else {
                        $btnLink = '<p class="configure_btn"> Another Door is selected for these quotation</p>';
                    }

                } else {
                    $btnLink =
                    '
                        <a href="javascript:void(0);" data-type="'.$ci->id.'"
                        class="configure_btn">Create <br>Door Type</a>
                        <a href="javascript:void(0);"
                        data-type="'.$ci->id.'" class="configure_btn configure_door_btn">Add
                        Additional <br> Door Type</a>
                    ';
                }

                $configItem .=
                '
                <div class="col-sm-6 p-0 pr-1">
                    <div class="Quote_tems">
                        <img src="'.url('/').'/images/'.$ci->img.'" style="height: 52;">
                        <a href="#">'.$ci->name.'</a>
                        <input type="hidden" value="'.$ci->id.'" class="configItemId">
                        <p>Configurable On Configuration</p>
                        '.$btnLink.'
                    </div>
                </div>
                ';
            }

            $countDeliveryAddressInEditHeader = QuotationSiteDeliveryAddress::where('QuotationId',$Id)->count();
            $xx = QuotationSiteDeliveryAddress::where('QuotationId',$Id)->get();
            $DA = '';
            $loop = 0;
            foreach($xx as $xxs){
                $plus = '';
                if($loop == 0){
                    $plus = '
                    <div>
                        <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add-customer-detail" class="btn-shadow btn btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>';
                } else {
                    $plus = '
                    <div>
                        <input type="hidden" class="QuotDeliverAddrID" value="'.$xxs->id.'">
                        <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" class="btn-shadow btn btn-danger deleteQuotDeliverAddr">
                            <i class="fa fa-remove"></i>
                        </a>
                    </div>';
                }

                $DA .= '
                <input type="hidden" name="quotation_sitedeliveryaddressID[]" value="'.$xxs->id.'">
                <div class="col-sm-12">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Site Delivery Address</h5>
                    </div>
                    '. $plus.'
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Address1">Address 1<span
                                        class="text-danger">*</span></label>
                                <input type="text" readonly class="form-control" name="Address1[]"
                                    value="'.$xxs->Address1.'" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Address2">Address 2</label>
                                <input type="text" readonly class="form-control" name="Address2[]"
                                    value="'.$xxs->Address2.'">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Country">Country</label>
                                <input type="text" readonly class="form-control" name="Country[]"
                                    value="'.$xxs->Country.'">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="City">City</label>
                                <input type="text" readonly class="form-control" name="City[]"
                                    value="'.$xxs->City.'">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="PostalCode">Postal Code/Eircode</label>
                                <input type="text" readonly class="form-control" name="PostalCode[]"
                                    value="'.$xxs->PostalCode.'">
                            </div>
                        </div>
                    </div>
                </div>

                ';
                $loop++;
            }

            $quotation_data = Quotation::where('id',$Id)->first();
            $currency = SettingCurrency::where('companyId',Auth::user()->CompanyId)->first();
            return view('DoorSchedule.Ordered',[
                'data' => $Schedule,
                'quotationId' => $Id,
                'version' => $Version,
                'maxVersion' => $MaxVersion,
                'orderNumber' => $Quotation->OrderNumber,
                'companykacustomer' => $companykacustomer,
                'customerMultiContact' => $customerMultiContact,
                'quotation' => $Quotation,
                'ProjectTable' => $Projects,
                'customerDetails' => $CustomerDetails,
                'QuotationContactInformation' => $QuotationContactInformation,
                'QuotationShipToInformation' => $QuotationShipToInformation,
                'additem' => $additem,
                'NonConfig' => $NonConfig,
                // 'TotalDoorPrice' => $TotalDoorPrice,
                'selectQV' => $selectQV,
                'configItem' => $configItem,
                'countDeliveryAddressInEditHeader' => $countDeliveryAddressInEditHeader,
                'QuotationSiteDeliveryAddress' => $xx,
                'DA' => $DA,
                'quotation_data' => $quotation_data,
                'nonConfigData' => $nonConfigData,
                'TotalDoorPrice' => $TotalDoorSetPrice,
                'TotalExactDoorPrice' => $TotalExactDoorPrice,
                'TotalIronmongeryPrice' => $TotalIronmongeryPrice,
                'total_price' => $total_price,
                'nonConfigDataPrice' => $nonConfigDataPrice,
                'currency' => $currency
            ]);
        } else {
            return redirect()->route('order/list');
        }
    }

    public function orderlistAllExport(){
        return Excel::download(new AllOrderExport(), 'Order.xlsx', \Maatwebsite\Excel\Excel::XLSX,
        [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

}
