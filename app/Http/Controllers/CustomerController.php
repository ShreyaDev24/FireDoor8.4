<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Company;
use App\Models\User;
use App\Models\CustomerContact;
use Session;
use DB;
use URL;
use Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(request $request){
        if(Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='1' ||Auth::user()->UserType=='4'){
        return view('Customer.AddCustomer');
        }
        else{
        return redirect()->route('contractor/list');
        }
    }

    public function list(request $request){
        if (Auth::user()->UserType=='2') {
            $data = Customer::where('UserId',Auth::user()->id)
                            ->orderBy('CstCompanyName','asc')->get()->toArray();
        } elseif (Auth::user()->UserType=='3') {
            $data = Customer::where('UserId',Auth::user()->id)
                            ->orderBy('CstCompanyName','asc')->get()->toArray();
        } elseif (Auth::user()->UserType=='1') {
            $data = Customer::orderBy('CstCompanyName','asc')->get()->toArray();
        }


        $tbl = "";

        if(!empty($data)){
            foreach($data as $row){

                $user = User::where('id',$row['UserId'])->first();

                $CompanyDetails = Company::where('UserId',$row['id'])->first();

                $MainCustomerDetails = CustomerContact::where('CustomerId',$row['id'])->orderBy('id','asc')->first();

                $FirstName = "";
                $LastName = "";
                $ContactEmail = "";
                $ContactPhone = "";

                if($MainCustomerDetails !== null){
                    $FirstName = $MainCustomerDetails->FirstName;
                    $LastName = $MainCustomerDetails->LastName;
                    $ContactEmail = $MainCustomerDetails->ContactEmail;
                    $ContactPhone = $MainCustomerDetails->ContactPhone;
                }

                $tbl .= '<tr>';
                $tbl .= '<td><a href="'.url('customer/details/'.$row['id']).'">'.$row['CstCompanyName'].'</a></td>
                    <td>'.$FirstName.' '.$LastName.' </td>
                    <td>'.$ContactEmail.'</td>
                    <td>'.$ContactPhone.'</td>
                    <td>'.$row['CstCompanyAddressLine1'].'</td>
                    <td><a href="'.url('customer/edit/'.$row['id']).'" class="btn btn-success"><i class="fa fa-pencil"></i></a></td>';
                if(!empty($user) && Auth::user()->UserType == '1'){

                if($user->UserType==2){
                $tbl .= '<td>'.$user->FirstName.' '.$user->LastName.' (Company) </td>';
                }

                elseif($user->UserType==3){
                    $tbl .= '<td>'.$user->FirstName.' '.$user->LastName.' (User) </td>';
                }

                elseif($user->UserType==4){
                    $tbl .= '<td>'.$user->FirstName.' '.$user->LastName.' (Architect) </td>';
                }
                }
                
                $tbl .= '</tr>';
            }
        }else{
            $tbl .= '<tr>';
            if(Auth::user()->UserType=='1'){
                $tbl .= '<td colspan="6">No record found.</td>';
            }else{
                $tbl .= '<td colspan="5">No record found.</td>';
            }
            
            $tbl .= '</tr>';
        }

        return view('Customer.CustomerList',['tbl' => $tbl]);
    }



    public function store(request $request){

        if(empty($request->FirstName)){
            $request->session()->flash('error','Customer details are required.');
            return redirect()->back();
        }

        if(property_exists($request, 'update') && $request->update !== null){
            $data = Customer::find($request->update);
        }else{

            $data = new Customer();
            $data->UserId = Auth::user()->id;
        }


        if($request->hasFile('CstCompanyPhoto')){
            $file = $request->file('CstCompanyPhoto');
            $name = time().$file->getClientOriginalName();
            $filepath = public_path('CompanyLogo');
            $file->move($filepath,$name);
            $data->CstCompanyPhoto = $name;
        }

        if(!empty($request->CstCompanyName) && !empty($request->CstCompanyEmail) && !empty($request->CstCompanyPhone) && !empty($request->CstCompanyAddressLine1) && !empty($request->CstCompanyCity) && !empty($request->CstCompanyState) && !empty($request->CstCompanyCountry) && !empty($request->CstCompanyPostalCode) && !empty($request->FirstName[0]) && !empty($request->LastName[0]) && !empty($request->ContactEmail[0]) && !empty($request->ContactJobTitle[0]) && !empty($request->ContactPhone[0])){

        $data->CstCompanyName = $request->CstCompanyName;
        $data->CstCompanyWebsite = $request->CstCompanyWebsite;
        $data->CstCompanyEmail = $request->CstCompanyEmail;
        $data->CstCompanyPhone = $request->CstCompanyPhone;
        $data->CstCompanyVatNumber = $request->CstCompanyVatNumber;
        $data->CstCompanyAddressLine1 = $request->CstCompanyAddressLine1;
        $data->CstCompanyAddressLine2 = $request->CstCompanyAddressLine2;
        $data->CstCompanyAddressLine3 = $request->CstCompanyAddressLine3;
        $data->CstSiteAddressLine1 = $request->CstSiteAddressLine1;
        $data->CstSiteAddressLine2 = $request->CstSiteAddressLine2;
        $data->CstSiteAddressLine3 = $request->CstSiteAddressLine3;
        $data->CstCompanyCity = $request->CstCompanyCity;
        $data->CstCompanyState = $request->CstCompanyState;
        $data->CstCompanyCountry = $request->CstCompanyCountry;
        $data->CstCompanyPostalCode = $request->CstCompanyPostalCode;
        $data->CstSiteCity = $request->CstSiteCity;
        $data->CstSiteState = $request->CstSiteState;
        $data->CstSiteCountry = $request->CstSiteCountry;
        $data->CstSitePostalCode = $request->CstSitePostalCode;
        $data->CstMoreInfo = $request->CstMoreInfo;

        $data->CstLat = $request->CstLat;
        $data->CstLong = $request->CstLong;

        if($request->CstCertification!=null){
          $data->CstCertification = implode(",", $request->CstCertification);
        }

        if($request->CstDeliveryDay!=null){
          $data->CstDeliveryDay = implode(",", $request->CstDeliveryDay);
        }

        if($request->CstSiteAvailability!=null){
          $data->CstSiteAvailability = implode(",", $request->CstSiteAvailability);
        }

        $data->CstDeliveryFromTime = $request->CstDeliveryFromTime;
        $data->CstDeliveryToTime = $request->CstDeliveryToTime;
        $data->CstDeliveryDeliveryType = $request->CstDeliveryDeliveryType;
        $data->CstDeliverySupplyType = $request->CstDeliverySupplyType;
        $data->CstDeliveryPaymentType = $request->CstDeliveryPaymentType;
        $data->CstDeliveryPaymentType = $request->CstDeliveryPaymentType;
        // $data->UserId = $user->id;
        $data->UserId = Auth::user()->id;
        $data->save();

        if (!empty($request->FirstName)) {
            $counter = count($request->FirstName);
            for($i = 0; $i < $counter; $i++){
                if($request->Id[$i] > 0){
                    $customer_contact = CustomerContact::find($request->Id[$i]);
                }else{
                    $customer_contact = new CustomerContact();
                }

                $customer_contact->FirstName = $request->FirstName[$i];
                $customer_contact->LastName = $request->LastName[$i];
                $customer_contact->ContactEmail = $request->ContactEmail[$i];
                $customer_contact->ContactJobTitle = $request->ContactJobTitle[$i];
                $customer_contact->ContactPhone = $request->ContactPhone[$i];
                $customer_contact->CustomerId = $data->id;
                $customer_contact->save();
            }
        }


        $request->session()->flash('success','Customer Added Successfully');
        // $request->session()->flash($flash, 'data');
        return redirect()->route('contractor/list');
    }else{
        $request->session()->flash('error','Please fill required field!');
        return redirect()->back(); 
    }
    }


    public function details($id){
        if(Auth::user()->UserType=='1' || Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4'){
            if(isset($id)){
                $data = Customer::where('id',$id)->first();
                $customer_contact = CustomerContact::where('CustomerId',$data->id)->get();
                if(!empty($data) && (array)$data !== []){
                    $data['auth']=Auth::user()->UserType;
                    return view('Customer.CustomerDetails',['data' => $data, 'customer_contact' => $customer_contact]);
                }else{
                    return redirect('customer/details');
                }

            }
            else{
                return redirect('customer/details');


            }
        }

        return null;
    }


    public function edit($id){
        if(Auth::user()->UserType=='1' || Auth::user()->UserType=='2' || Auth::user()->UserType=='3'){
            if(isset($id)){

                $editdata = Customer::where('customers.id',$id)->first();

                if(!empty( $editdata) && (array)$editdata !== []){
                    $DDvalue=explode(",",(string) $editdata->CstCertification);

                    json_encode(in_array("LEED", $DDvalue));

                    $CustomerContactDetails = CustomerContact::where('CustomerId',$id)->orderBy('id','asc')->get();

                    return view('Customer.AddCustomer',['editdata' => $editdata, 'CustomerContactDetails' => $CustomerContactDetails]);
                }else{
                    return redirect()->route('contractor/list');
                }
            }else{
                return redirect()->route('contractor/list');
            }

        }else{
            return redirect()->route('contractor/list');
        }
    }

}
