<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Company;
use App\Models\User;
use App\Models\CustomerContact;
use App\Models\Users;
use Session;
use DB;
use URL;
use Hash;
use Mail;

class ContractorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(request $request)
    {
        if (Auth::user()->UserType == '1' || Auth::user()->UserType == '2' || Auth::user()->UserType == '4') {
            return view('Contractor.AddContractor');
        } else {
            return redirect()->route('contractor/list');
        }
    }

    public function list(request $request)
    {

        // if (Auth::user()->UserType == '1') {
        //     $data = User::join('customers','customers.UserId','users.id')->where('users.UserType',5)->orderBy('customers.CstCompanyName','asc')->get()->toArray();
        // } elseif (Auth::user()->UserType == '3') {
        //     $created_by_my_cmpny_admin_user = User::where('CreatedBy', Auth::user()->CreatedBy)->where('UserType',3)->pluck('id')->toArray();
        //     array_push($created_by_my_cmpny_admin_user, intval(Auth::user()->CreatedBy));

        //     $data = Customer::join('users', 'users.id', 'customers.UserId')->join('company_contractor','company_contractor.MainContractorId','users.id')->select('customers.*', 'users.UserEmail')->wherein('company_contractor.CompnayId', $created_by_my_cmpny_admin_user)->orderBy('customers.CstCompanyName', 'asc')->get()->toArray();
        // }elseif(Auth::user()->UserType == '2'){
        //     $created_by_me_users = User::where('CreatedBy', Auth::user()->id)->where('UserType',3)->pluck('id')->toArray();
        //     array_push($created_by_me_users, Auth::user()->id);
        //     $data = Customer::join('users', 'users.id', 'customers.UserId')->join('company_contractor','company_contractor.MainContractorId','users.id')->select('customers.*', 'users.UserEmail')->whereIn('company_contractor.CompnayId', $created_by_me_users)->orderBy('customers.CstCompanyName', 'asc')->get()->toArray();
        // } else {
        //     $data = Customer::join('users', 'users.id', 'customers.UserId')
        //         ->select('customers.*', 'users.UserEmail')->where('users.CreatedBy', Auth::user()->id)->orderBy('customers.CstCompanyName', 'asc')->get()->toArray();
        // }
// dd(Auth::user()->UserType);
        if (Auth::user()->UserType == '1') {
            $data = User::join('customers','customers.UserId','users.id')->where('users.UserType',5)->orderBy('customers.CstCompanyName','asc')->get()->toArray();
        } elseif (Auth::user()->UserType == '3') {
            $created_by_my_cmpny_admin_user = myCreatedUser();
            
            $data = User::join('customers','customers.UserId','users.id')->where('users.UserType',5)->orderBy('customers.CstCompanyName','asc')->wherein('users.CreatedBy', $created_by_my_cmpny_admin_user)->get()->toArray();

        }elseif(Auth::user()->UserType == '2'){

            $created_by_me_users = myCreatedUser();
            
            $data = Customer::join('users', 'users.id', 'customers.UserId')
                ->select('customers.*', 'users.UserEmail')->whereIn('users.CreatedBy', $created_by_me_users)->orderBy('customers.CstCompanyName', 'asc')->get()->toArray();
        } else {
            $data = Customer::join('users', 'users.id', 'customers.UserId')
                ->select('customers.*', 'users.UserEmail')->where('users.CreatedBy', Auth::user()->id)->orderBy('customers.CstCompanyName', 'asc')->get()->toArray();
        }

        $tbl = "";

        if (!empty($data)) {
            foreach ($data as $row) {


                $MainContractorDetails = CustomerContact::where('MainContractorId', $row['id'])->orderBy('id', 'asc')->first();
                $FirstName = "";
                $LastName = "";
                $ContactEmail = "";
                $ContactPhone = "";

                if ($MainContractorDetails !== null) {
                    $FirstName = $MainContractorDetails->FirstName;
                    $LastName = $MainContractorDetails->LastName;
                    $ContactEmail = $row['CstCompanyEmail'];
                    $ContactPhone = $MainContractorDetails->ContactPhone;
                }

                $action = '<td></td>';
                if (Auth::user()->UserType == '2' || Auth::user()->UserType == '3') {
                    $action = '<td style="width: 100px"><a href="' . url('contractor/edit/' . $row['id']) . '" class="btn btn-success mr-1"><i class="fa fa-pencil"></i></a><a href="javascript:void(0);" onclick="deleteContractor(' . $row['id'] . ');" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>';
                } else {
                    $action = '<td style="width: 100px"><a href="' . url('contractor/edit/' . $row['id']) . '" class="btn btn-success"><i class="fa fa-pencil"></i></a></td>';
                }

                $tbl .= '<tr>';
                $tbl .= '<td><a href="' . url('contractor/details/' . $row['id']) . '">' . $row['CstCompanyName'] . '</a></td>
                    <td>' . $FirstName . ' ' . $LastName . ' </td>
                    <td>' . $ContactEmail . '</td>
                    <td>' . $ContactPhone . '</td>
                    <td>' . $row['CstCompanyAddressLine1'] . '</td>'
                    . $action;

                // if(Auth::user()->UserType=='1'){
                //     $tbl .= '<td>'.$CompanyDetails->CompanyName.'</td>';
                // }

                $tbl .= '</tr>';
            }
        } else {
            $tbl .= '<tr>';
            if (Auth::user()->UserType == '1') {
                $tbl .= '<td colspan="6">No record found.</td>';
            } else {
                $tbl .= '<td colspan="5">No record found.</td>';
            }
            
            $tbl .= '</tr>';
        }

        return view('Contractor.ContractorList', ['tbl' => $tbl]);
    }



    public function store(request $request)
    {

        if (!empty($request->CstCompanyName) && !empty($request->CstCompanyEmail) && !empty($request->CstCompanyPhone) && !empty($request->CstCompanyAddressLine1) && !empty($request->CstCompanyCity) && !empty($request->CstCompanyState) && !empty($request->CstCompanyCountry) && !empty($request->CstCompanyPostalCode) && !empty($request->FirstName[0]) && !empty($request->LastName[0]) && !empty($request->ContactEmail[0]) && !empty($request->ContactJobTitle[0]) && !empty($request->ContactPhone[0]) && !empty($request->count[0])) {
            $counter = count($request->count);
            for($x = 0; $x < $counter; $x++){
                if(empty($request->FirstName[$x]) || empty($request->LastName[$x]) || empty($request->ContactEmail[$x]) || empty($request->ContactJobTitle[$x]) || empty($request->ContactPhone[$x])){
                    $request->session()->flash('contacterror', 'Please fill required field!');
                    return redirect()->back();
                }
            }

            if (property_exists($request, 'update') && $request->update !== null) {
                $data = Customer::find($request->update);
                $user = User::where('id', $data->UserId)->first();
            } else {

                $data = new Customer();
                $user = new User();
                Validator::extend('without_spaces', function ($attr, $value) {
                    return preg_match('/^\S*$/u', $value);
                });
                $request->validate([
                    'CstCompanyName' => 'required',
                    'CstCompanyAddressLine1' => 'required',
                    'CstCompanyCountry' => 'required',
                    'CstCompanyState' => 'required',
                    'CstCompanyCity' => 'required',
                    'CstCompanyPostalCode' => 'required',
                    'FirstName' => 'required',
                    'LastName' => 'required',
                    'ContactJobTitle' => 'required',

                    // 'CstCompanyEmail' => 'required|unique:users,UserEmail',
                ]);
            }

            $user->UserType = "5";
            $user->FirstName = $request->CstCompanyName;
            $user->CreatedBy = Auth::user()->id;
            $user->UserEmail = $request->CstCompanyEmail;
            if (Auth::user()->UserType != '5') {
                $password = random_int(10000, 100000);
                $user->password = Hash::make($password);
                if ($request->sendMail == 1) {
                    $emailTo = $request->CstCompanyEmail;
                    $subject = 'Login Password';
                    $emailFrom = 'noreply@jfds.co.uk';
                    $usermname = $request->CstCompanyName;
                    $data_set = ['usermname' => $usermname, 'pass' => $password];

                    ini_set('display_errors', 1);
                    try {
                        Mail::send(['html' => 'Mail.Password'], $data_set, function ($message) use (&$emailTo, &$subject, &$emailFrom): void {

                            $message->to($emailTo, $emailTo)->subject($subject);
                            if ($emailFrom !== '') {
                                $message->from($emailFrom, $emailFrom);
                            }
                        });
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }

            $user->save();
            // sending_mail_credential($user->UserEmail, $request->password);
            if ($request->hasFile('CstCompanyPhoto')) {
                $file = $request->file('CstCompanyPhoto');
                $name = time() . $file->getClientOriginalName();
                $filepath = public_path('CompanyLogo');
                $file->move($filepath, $name);
                $data->CstCompanyPhoto = $name;
            }

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
            if ($request->CstCertification != null) {
                $data->CstCertification = implode(",", $request->CstCertification);
            }

            if ($request->CstDeliveryDay != null) {
                $data->CstDeliveryDay = implode(",", $request->CstDeliveryDay);
            }

            if ($request->CstSiteAvailability != null) {
                $data->CstSiteAvailability = implode(",", $request->CstSiteAvailability);
            }

            $data->CstDeliveryFromTime = $request->CstDeliveryFromTime;
            $data->CstDeliveryToTime = $request->CstDeliveryToTime;
            $data->CstDeliveryDeliveryType = $request->CstDeliveryDeliveryType;
            $data->CstDeliverySupplyType = $request->CstDeliverySupplyType;
            $data->CstDeliveryPaymentType = $request->CstDeliveryPaymentType;
            $data->CstDeliveryPaymentType = $request->CstDeliveryPaymentType;
            $data->UserId = $user->id;
            $data->save();
            if (!empty($request->FirstName)) {
                $counter = count($request->FirstName);
                for ($i = 0; $i < $counter; $i++) {
                    if ($request->Id[$i] > 0) {
                        $contractor_contact = CustomerContact::find($request->Id[$i]);
                    } else {
                        $contractor_contact = new CustomerContact();
                    }
                    
                    if (!empty($request->FirstName[$i]) || !empty($request->LastName[$i]) || !empty($request->ContactEmail[$i]) || !empty($request->ContactJobTitle[$i]) || !empty($request->ContactPhone[$i])) {
                        $contractor_contact->FirstName = $request->FirstName[$i];
                        $contractor_contact->LastName = $request->LastName[$i];
                        $contractor_contact->ContactEmail = $request->ContactEmail[$i];
                        $contractor_contact->ContactJobTitle = $request->ContactJobTitle[$i];
                        $contractor_contact->ContactPhone = $request->ContactPhone[$i];
                        $contractor_contact->MainContractorId = $data->id;
                        $contractor_contact->save();
                    } else {
                        $request->session()->flash('error', "Please Fill Company's Other Contact");
                    }
                }
            } else {
                $request->session()->flash('error', "Please Fill Company's Other Contact");
            }

            $request->session()->flash('success', 'Contractor Added Successfully');
            if (Auth::user()->UserType == '5') {
                return redirect('contractor/profile');
            } else {
                return redirect()->route('contractor/list');
            }
        } else {
            $request->session()->flash('contacterror', 'Please fill required field!');
            return redirect()->back();
        }
    }



    public function details($id)
    {
        markAsRead($id, 'contractor');
        if (Auth::user()->UserType == '1' || Auth::user()->UserType == '2' || Auth::user()->UserType == '3' || Auth::user()->UserType == '4') {
            if (isset($id)) {
                $data = Customer::where('id', $id)->first();
                $customer_contact = CustomerContact::where('MainContractorId', $data->id)->get();
                if (!empty($data) && (array)$data !== []) {
                    $data['auth'] = Auth::user()->UserType;
                    return view('Contractor.ContractorDetails', ['data' => $data, 'customer_contact' => $customer_contact]);
                } else {
                    return redirect('contractor/details');
                }
            } else {
                return redirect('contractor/details');
            }
        }

        return null;
    }


    public function edit($id)
    {
        if (Auth::user()->UserType == '1' || Auth::user()->UserType == '2' || Auth::user()->UserType == '4' || Auth::user()->UserType == '3' || Auth::user()->UserType == '5') {
            if (isset($id)) {

                $editdata = Customer::where('customers.id', $id)->first();

                if (!empty($editdata) && (array)$editdata !== []) {
                    $DDvalue = explode(",", $editdata->CstCertification);
                    json_encode(in_array("LEED", $DDvalue));
                    $ContractorContactDetails = CustomerContact::where('MainContractorId', $id)->orderBy('id', 'asc')->get();
                    return view('Contractor.AddContractor', ['editdata' => $editdata, 'ContractorContactDetails' => $ContractorContactDetails]);
                } elseif (Auth::user()->UserType == '5') {
                    return redirect()->route('contractor/profile');
                } else {
                    return redirect()->route('contractor/list');
                }
            } else {
                return redirect()->route('contractor/list');
            }
        } else {
            return redirect()->route('contractor/list');
        }
    }

    public function profile(request $request)
    {
        if (Auth::user()->UserType == '5') {

            $editdata = Users::where('id', Auth::user()->id)->first();

            if (!empty($editdata) && (array)$editdata !== []) {

                $customer = Users::join('customers', 'customers.CstCompanyEmail', '=', 'users.UserEmail')->join('customer_contacts', 'customer_contacts.MainContractorId', '=', 'customers.id')->where('users.id', Auth::user()->id)->where('customers.UserId', Auth::user()->id)->select('customers.*')->get()->first();

                $data = Customer::where('id', $customer->id)->first();
                $customer_contact = CustomerContact::where('MainContractorId', $data->id)->get();

                if (!empty($data) && (array)$data !== []) {
                    $data['auth'] = Auth::user()->UserType;
                    return view('Contractor.ContractorDetails', ['data' => $data, 'customer_contact' => $customer_contact]);
                } else {
                    return redirect('contractor/details');
                }
            }
        }

        return null;
    }

    public function deleteContractor(Request $request)
    {
        $ContractorId = $request->ContractorId;
        if (!empty($ContractorId)) {
            $CustomerContact = CustomerContact::where('MainContractorId', $ContractorId)->get();
            $Customer = Customer::where('id', $ContractorId)->first();
            $Users = Users::where('id', $Customer->UserId)->first();
            foreach ($CustomerContact as $val) {
                CustomerContact::where('id', $val->id)->delete();
            }
            
            $Customer->delete();
            $Users->delete();
            return redirect()->back()->with('successed', 'The Main Contractor deleted successfully!');
        }

        return null;
    }
}
