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
use Mail;
use App\Models\ProjectInvitation;
use App\Models\AddIronmongery;
use App\Models\BOMSetting;
use App\Models\Company;
use App\Models\CompanyQuotationCounter;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\Project;
use App\Models\ProjectFiles;
use App\Models\ProjectFilesDS;
use App\Models\Quotation;
use App\Models\QuotationContactInformation;
use App\Models\QuotationShipToInformation;
use App\Models\QuotationSiteDeliveryAddress;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\SelectedColor;
use App\Models\SelectedIronmongery;
use App\Models\SelectedOption;
use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFDocument;
use App\Models\SettingPDFfooter;
use App\Models\SettingBOMCost;
use App\Models\ShippingAddress;
use App\Models\User;

use App\Models\ArchtectureItemForms;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function add()
    {
        if(Auth::user()->UserType=='1'){
            return view('Company.AddCompany');
        }
    }
    public function list()
    {
        if(Auth::user()->UserType=='1'){
            $data = Company::join('users','users.id','companies.UserId')->select('users.FirstName','users.LastName','users.UserEmail','users.UserPhone','companies.*')->where('UserType','2')->OrderBy('id','desc')->get();
            return view('Company.CompanyList',compact('data'));
        }else if(Auth::user()->UserType=='2'){
            return redirect()->route('company/profile');
        } else {
            return redirect('company/details/'.Auth::user()->id);
        }
    }



    public function details($id)
    {
        $userid = Company::where('id',$id)->value('UserId');
        $user_count = User::where('CreatedBy',$userid)->where('UserType',3)->count();

        if(Auth::user()->UserType=='1'){
            if(!empty($id)){
                $data = Company::join('users','users.id','companies.UserId')->select('users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle' ,'users.UserPhone','companies.*')->where('companies.id',$id)->first();
                return view('Company.CompanyDetails',compact('data','user_count'));
            } else {
                return redirect()->route('company/list');
            }
        }

        else if(Auth::user()->UserType=='2'){
            $id = Auth::user()->id;
            $data = Company::join('users','users.id','companies.UserId')->select('users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle' ,'users.UserPhone','companies.*')->where('companies.id',$id)->first();
            $data['auth']=Auth::user()->UserType;
            return view('Company.CompanyDetails',compact('data'));
        }
        else if(Auth::user()->UserType=='3'){
            $data = User::join('companies','companies.UserId','users.id')->join('customers','customers.UserId','users.id')
            ->select('users.FirstName','users.LastName','users.UserEmail','users.UserPhone','customers.*')
            ->where('users.id',Auth::user()->id)
            ->first();
            // print_r( $data);
            // die();
            return view('Customer.CustomerDetails',compact('data'));
        }
    }

    public function profile()
    {
       if(Auth::user()->UserType=='2'){

            $id = Auth::user()->id;
            $user_count = User::where('CreatedBy',$id)->where('UserType',3)->count();
            $data = Company::join('users','users.id','companies.UserId')->select('users.id as userId','users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle','users.UserPhone','companies.*')->where('companies.UserId',$id)->first();
            $data['auth']=Auth::user()->UserType;

            return view('Company.CompanyDetails',compact('data','user_count'));

        }
    }


    public function store(request $request)
    {
        $status_mail = false;
        if(isset($request->update)){
            $data = Company::where('UserId',$request->update)->first();
            $user = User::where('id',$request->update)->first();
            $flash = "updated";
        } else {
            $data = new Company();
            $user = new User();
            $flash = "added";
            $this->validate( $request,[
                'UserEmail'=> "required|email|unique:users",
            ]);
            $status_mail = true;
            $length = 10;
            $pass = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1,$length);
        }

        // if($request->hasFile('CompanyLogo')){

        //     $this->validate( $request,[
        //         'CompanyLogo' => 'mimes:jpeg,png,jpg|max:1096|image|dimensions:width=200,height=100',
        //     ],[
        //         'CompanyLogo.dimensions' => 'The image must be exactly 200*100 pixcels.'
        //     ]

        //     );

        //     $file = $request->file('CompanyLogo');
        //     $name = time().$file->getClientOriginalName();
        //     $filepath = public_path('CompanyLogo/');

        //     $path = $file;
        //     $type = pathinfo($path, PATHINFO_EXTENSION);
        //     $filedata = file_get_contents($path);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);

        //     $file->move($filepath,$name);

        //     if(isset($request->update)){
        //         File::delete($filepath.$data->CompanyPhoto);
        //     }

        //     $data->CompanyPhoto = $name;
        //     $data->ComplogoBase64 = $base64;
        //     $user->UserImage = $name;
        // }

        // resize image code (200*100)
        if ($request->hasFile('CompanyLogo')) {

            $this->validate($request, [
                'CompanyLogo' => 'required|mimes:jpeg,png,jpg|max:1096|image',
            ]);

            $file = $request->file('CompanyLogo');
            $name = time() . '_' . $file->getClientOriginalName();
            $filepath = public_path('CompanyLogo/');

            // Move the uploaded file to a temporary location
            $tempPath = $filepath . 'temp_' . $name;
            $file->move($filepath, 'temp_' . $name);

            // Load the temporary image and resize it
            list($originalWidth, $originalHeight) = getimagesize($tempPath);
            $src = imagecreatefromstring(file_get_contents($tempPath));

            // Set the desired width and height
            $desiredWidth = 200;
            $desiredHeight = 100;

            // Create a new blank image with the desired width and height
            $dst = imagecreatetruecolor($desiredWidth, $desiredHeight);

            // Handle transparency for PNG and GIF
            if ($file->getClientOriginalExtension() === 'png' || $file->getClientOriginalExtension() === 'gif') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefilledrectangle($dst, 0, 0, $desiredWidth, $desiredHeight, $transparent);
            } else {
                // Fill the background with white for JPEG or other formats
                $background = imagecolorallocate($dst, 255, 255, 255); // white
                imagefilledrectangle($dst, 0, 0, $desiredWidth, $desiredHeight, $background);
            }

            // Resize the original image into the new image
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

            // Save the resized image based on its original type
            $finalPath = $filepath . $name;
            switch ($file->getClientOriginalExtension()) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($dst, $finalPath);
                    break;
                case 'png':
                    imagepng($dst, $finalPath);
                    break;
                case 'gif':
                    imagegif($dst, $finalPath);
                    break;
            }

            // Free up memory
            imagedestroy($src);
            imagedestroy($dst);

            // Remove the temporary image
            unlink($tempPath);

            // Convert the resized image to a Base64 string
            $type = pathinfo($finalPath, PATHINFO_EXTENSION);
            $filedata = file_get_contents($finalPath);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);

            // If updating, delete the old file
            if (isset($request->update)) {
                File::delete($filepath . $data->CompanyPhoto);
            }

            // Save the new image data to the model
            $data->CompanyPhoto = $name;
            $data->ComplogoBase64 = $base64;
            $user->UserImage = $name;
        }



        if(!empty($request->CompanyName) && !empty($request->CompanyEmail) && !empty($request->CompanyPhone) && !empty($request->CompanyAddressLine1) && !empty($request->CompanyCity) && !empty($request->CompanyState) && !empty($request->CompanyCountry) && !empty($request->CompanyPostalCode) && !empty($request->FirstName) && !empty($request->LastName) && !empty($request->UserEmail) &&!empty($request->UserJobtitle) &&!empty($request->UserPhone)){

            $data->CompanyName = $request->CompanyName;
            $data->CompanyWebsite = $request->CompanyWebsite;
            $data->CompanyEmail = $request->CompanyEmail;
            $data->CompanyPhone = $request->CompanyPhone;
            $data->CompanyVatNumber = $request->CompanyVatNumber;
            $data->CompanyAddressLine1 = $request->CompanyAddressLine1;
            $data->CompanyAddressLine2 = $request->CompanyAddressLine2;
            $data->CompanyAddressLine3 = $request->CompanyAddressLine3;
            $data->CompanyCity = $request->CompanyCity;
            $data->CompanyState = $request->CompanyState;
            $data->CompanyCountry = $request->CompanyCountry;
            $data->CompanyPostalCode = $request->CompanyPostalCode;
            $data->CompanyMoreInfo = $request->CompanyMoreInfo;

            $data->CompanyAddressLine1 = $request->CompanyAddressLine1;
            $data->CompanyAddressLine2 = $request->CompanyAddressLine2;
            $data->CompanyAddressLine3 = $request->CompanyAddressLine3;

            $data->Lat = $request->Lat;
            $data->Long = $request->Long;
            $data->save();

            $user->FirstName = $request->FirstName;
            $user->LastName =  $request->LastName;
            $user->UserEmail = $request->UserEmail;
            $user->UserJobtitle = $request->UserJobtitle;
            $user->UserPhone = $request->UserPhone;
            $user->UserType = 2;
            $user->CreatedBy = Auth::user()->id;

            if($request->sendMail == 1){
                $status_mail = true;
                $pass = $request->newpassword;
            }
            if($status_mail == true){
                $emailTo = $request->UserEmail;
                $subject = 'Login Password';
                $emailFrom = 'noreply@jfds.co.uk';
                $usermname = $request->FirstName.' '.$request->LastName;
                $data_set = ['usermname'=>$usermname,'pass'=>$pass];
                $user->password =  Hash::make($pass);
                ini_set('display_errors', 1);
                try{
                    Mail::send(['html' => 'Mail.Password'], $data_set, function($message) use(&$emailTo, &$subject, &$emailFrom) {

                        $message->to($emailTo, $emailTo)->subject($subject);
                        if($emailFrom){
                            $message->from($emailFrom, $emailFrom);
                        }

                    });

                } catch (Exception $e) {
                        echo $e->getMessage();
                }
            }

            $user->save();

            $userid = User::find($user->id);
            $userid->parent_id = $user->id;
            $userid->save();

            if(isset($request->update)){
                $data = Company::where('UserId',$request->update)->first();
            }

            $email = $request->UserEmail;

            if(empty($request->update)){
                $lo = Company::orderBy('id','desc')->limit(1)->first();
                // insert into Mail Formate
                $p1 = new SettingPDF1();
                $p2 = new SettingPDF2();
                $p3 = new SettingPDFfooter();
                $p4 = new SettingPDFDocument();

                $p1->UserId = $user->id;
                $p2->UserId = $user->id;
                $p3->UserId = $user->id;
                $p4->UserId = $user->id;
                $data->UserId = $user->id;

                $data->save();


                $p1->msg = '<table border="0" cellpadding="1" cellspacing="1" style="width:300px">
                        <tbody>
                            <tr>
                                <td>[CustomerAddress]</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>&nbsp;</p>

                    <table border="0" cellpadding="1" cellspacing="1" style="width:500px">
                        <tbody>
                            <tr>
                                <td>Date</td>
                                <td>[Date]</td>
                            </tr>
                            <tr>
                                <td>FTAO</td>
                                <td>[CustomerName]</td>
                            </tr>
                            <tr>
                                <td>RE</td>
                                <td>[ProjectName]</td>
                            </tr>
                            <tr>
                                <td>Our Ref</td>
                                <td>[QuotationGenerationId]</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>Dear [CustomerName],</p>

                    <p>We thank you for the valued enquiry and the opportunity to quote on this project.</p>

                    <p>The attached quotation is for the supply and delivery only of Doorsets and Ironmongery as detailed on the summary page. The quotation is based on our interpretation of the requirements as detailed in your enquiry and anything quoted outside the specification is noted on the schedule.</p>

                    <p>We trust this offer meets your approval and we look forward to contacting you soon to discuss this further.</p>

                    <p>&nbsp;</p>

                    <p>Yours Sincerely</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <table border="0" cellpadding="1" cellspacing="1" style="width:500px">
                        <tbody>
                            <tr>
                                <td><strong>[UserName]</strong></td>
                            </tr>
                            <tr>
                                <td><strong>[Designation]</strong></td>
                            </tr>
                            <tr>
                                <td><strong>E: [UserEmail]</strong></td>
                            </tr>
                            <tr>
                                <td><strong>M: [UserMobile]</strong></td>
                            </tr>
                        </tbody>
                    </table>';
                $p1->created_at = date('Y-m-d H:i:s');
                $p1->updated_at = date('Y-m-d H:i:s');
                $p1->save();


                $p2->msg = '<p><span style="font-size:36px"><strong>[ProjectName]</strong></span></p>

                <p><span style="font-size:36px"><strong>[QuotationGenerationId] -&nbsp;&nbsp;Quotation Summary</strong></span></p>

                <p>&nbsp;</p>

                <p><span style="font-size:18px">This offer is for: <strong>Doorsets and Ironmongery</strong></span></p>

                <p>&nbsp;</p>

                <table cellpadding="1" cellspacing="1" style="width:500px">
                    <tbody>
                        <tr>
                            <td><span style="font-size:18px">Total no. of Doorsets: </span></td>
                            <td style="text-align:right"><span style="font-size:18px">[TotalDoorSet]</span></td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Total no. of Ironmongery sets:</span></td>
                            <td style="text-align:right"><span style="font-size:18px">[TotalIronmongery]</span></td>
                        </tr>
                        <tr>
                            <td>Total no. of Non Configurable sets:</td>
                            <td>[TotalNonConfig]</td>
                        </tr>
                        <tr>
                            <td>Total no. of Screen sets:</td>
                            <td>[TotalScreenSet]</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Total Doorset Value:</span></td>
                            <td style="text-align:right"><span style="font-size:18px">&pound;[TotalDoorValue]</span></td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Total Ironmongery figure:</span></td>
                            <td style="text-align:right"><span style="font-size:18px">&pound;[TotalIronmongeryValue]</span></td>
                        </tr>
                        <tr>
                            <td>Total Non Configurable Value:</td>
                            <td>&pound;[TotalNonConfigValue]</td>
                        </tr>
                        <tr>
                            <td>Total ScreenSet Value:</td>
                            <td>&pound;[TotalScreenValue]</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="height:50px"><span style="font-size:18px"><strong>Net Total: </strong></span></td>
                            <td style="text-align:right"><span style="font-size:18px"><strong>&pound;[NetTotal]</strong></span></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height:50px; vertical-align:top"><span style="font-size:18px">Prices fixed for 3 months from date of this quote.</span></td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Number of deliveries included is:</span></td>
                            <td style="text-align:right">
                            <div><span style="font-size:20px"><strong>[NoOfDeliveries]</strong></span></div>
                            </td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Payment terms</span></td>
                            <td style="text-align:right">
                            <div><span style="font-size:18px">[PaymentTerms]</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td><span style="font-size:18px">Estimator:</span></td>
                            <td style="text-align:right"><span style="font-size:18px">[UserName]</span></td>
                        </tr>
                    </tbody>
                </table>';
                $p2->created_at = date('Y-m-d H:i:s');
                $p2->updated_at = date('Y-m-d H:i:s');
                $p2->save();


                $p3->msg = '<p>&nbsp;</p>

                <p>&nbsp;</p>

                <table border="1" cellpadding="1" cellspacing="1" style="width:100%">
                    <tbody>
                        <tr>
                            <td>
                            <p><span style="color:#8e44ad">[CompanyName]</span></p>

                            <p>[CompanyAddress]</p>
                            </td>
                            <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <p>Telephone: [CompanyPhone]&nbsp;<span style="color:#8e44ad">|</span>&nbsp;Email: [CompanyEmail]<span style="color:#8e44ad">&nbsp;| </span>Web: [CompanyWebsite]</p>';
                $p3->created_at = date('Y-m-d H:i:s');
                $p3->updated_at = date('Y-m-d H:i:s');
                $p3->save();

                $p4->msg = '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
                        <tbody>
                            <tr>
                                <td>1. Interpretation 1.1 Definitions: &quot;Business Day&rdquo; means a day (other than a Saturday, Sunday or public holiday) when banks in London are open for business. &quot;Conditions&quot; means the terms and conditions set out in this document as amended from time to time in accordance with clause 11.4. &quot;Contract&quot; means the contract between the Supplier and the Customer for the sale and purchase of the Goods in accordance with these Conditions. &quot;Customer&quot; means the person, company, business or firm who purchases the Goods from the Supplier. &quot;Force Majeure Event&quot; means any circumstance not within a party&#39;s reasonable control including, without limitation: A) acts of God, flood, drought, earthquake or other natural disaster; b) epidemic or pandemic; c) terrorist attack, civil war, civil commotion or riots, war, threat of or preparation for war, armed conflict, imposition of sanctions, embargo, or breaking off of diplomatic relations; d) nuclear, chemical or biological contamination or sonic boom; e) any law or any action taken by a government or public authority, including without limitation imposing an export or import restriction, quota or prohibition, or failing to grant a necessary licence or consent; f) collapse of buildings, fire, explosion or accident; and g) any labour or trade dispute, strikes, industrial action or lockouts (other than in each case by the party seeking to rely on this clause, or companies in the same group as that party).</td>
                                <td>1. Interpretation 1.1 Definitions: &quot;Business Day&rdquo; means a day (other than a Saturday, Sunday or public holiday) when banks in London are open for business. &quot;Conditions&quot; means the terms and conditions set out in this document as amended from time to time in accordance with clause 11.4. &quot;Contract&quot; means the contract between the Supplier and the Customer for the sale and purchase of the Goods in accordance with these Conditions. &quot;Customer&quot; means the person, company, business or firm who purchases the Goods from the Supplier. &quot;Force Majeure Event&quot; means any circumstance not within a party&#39;s reasonable control including, without limitation: A) acts of God, flood, drought, earthquake or other natural disaster; b) epidemic or pandemic; c) terrorist attack, civil war, civil commotion or riots, war, threat of or preparation for war, armed conflict, imposition of sanctions, embargo, or breaking off of diplomatic relations; d) nuclear, chemical or biological contamination or sonic boom; e) any law or any action taken by a government or public authority, including without limitation imposing an export or import restriction, quota or prohibition, or failing to grant a necessary licence or consent; f) collapse of buildings, fire, explosion or accident; and g) any labour or trade dispute, strikes, industrial action or lockouts (other than in each case by the party seeking to rely on this clause, or companies in the same group as that party).</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>&nbsp;</p>';
                $p4->created_at = date('Y-m-d H:i:s');
                $p4->updated_at = date('Y-m-d H:i:s');
                $p4->save();

            }

            if(!empty($request->update)){
                $countEmail = User::where('UserEmail',$email)->count();
                if($countEmail == 0){
                    $user->password =  Hash::make($pass);


                    // Send Mail to Company Admin
                        $to = $request->UserEmail;
                        $fullname = $request->FirstName.' '. $request->LastName;

                        // $data = ['email'=>$to];
                        // Mail::send('Mail.OTP',$data,function($message) use ($data){
                        //     $message->to($to,$fullname);
                        //     $message->from('firedoor@workdemo.online','firedoor');
                        // });

                        $subject = "New Password | Joinery fire door software.";
                        $msg = "
                            <html>
                            <body>
                                <b>Welcome to JFDS!</b>

                                <p>It\'s great to have you onboard</p>

                                <p><b>Hi $fullname </b></p>

                                <p>
                                    Thank you for signing up! JFDS has everything you need to stay fully compliant when manufacturing fire doors, produce full quotations with CAD and section drawings, and much more.
                                </p>
                                <p><b>Your JFDS login details</b></p>

                                <p><b>Username:</b> $to </p>

                                <p><b>Password:</b> $pass </p>
                                <p>Please don\'t hesitate to get in touch if you have any questions. We\'re here to help!</p>
                                <p>Cheers,</p>
                                <p>The team at JFDS</p>
                            </body>
                            </html>
                        ";



                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= "From: mail.jfds.co.uk";
                        // mail($to,$subject,stripslashes($msg),$headers);
                }
            } else {



                // Send Mail to Company Admin
                    $to = $request->UserEmail;
                    $fullname = $request->FirstName.' '. $request->LastName;

                    // $data = ['email'=>$to];
                    // Mail::send('Mail.OTP',$data,function($message) use ($data){
                    //     $message->to($to,$fullname);
                    //     $message->from('firedoor@workdemo.online','firedoor');
                    // });

                    $subject = "New Password | Joinery fire door software.";
                    $msg = "
                        <html>
                        <body>
                            <b>Welcome to JFDS!</b>

                            <p>It\'s great to have you onboard</p>

                            <p><b>Hi $fullname </b></p>

                            <p>
                                Thank you for signing up! JFDS has everything you need to stay fully compliant when manufacturing fire doors, produce full quotations with CAD and section drawings, and much more.
                            </p>
                            <p><b>Your JFDS login details</b></p>

                            <p><b>Username:</b> $to </p>

                            <p><b>Password:</b> $pass </p>
                            <p>Please don\'t hesitate to get in touch if you have any questions. We\'re here to help!</p>
                            <p>Cheers,</p>
                            <p>The team at JFDS</p>
                        </body>
                        </html>
                    ";



                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= "From: mail.jfds.co.uk";
                    // mail($to,$subject,stripslashes($msg),$headers);
            }


            $request->session()->flash($flash, 'data');
            return redirect()->route('company/list');
        }else{
            $request->session()->flash('error','Please fill required field!');
            return redirect()->back();
        }

    }



    public function edit($id)
    {
        if(Auth::user()->UserType=='1' || Auth::user()->UserType=='2'){
            if(isset($id)){
                $editdata = Company::join('users','users.id','companies.UserId')->select('users.FirstName','users.LastName', 'users.UserEmail','users.UserPhone','users.UserJobtitle','users.UserImage','companies.*')->where('companies.id',$id)->first();
                if(!empty($editdata) && count((array)$editdata)>0)
                {
                    return view('Company.AddCompany',compact('editdata'));
                } else {
                    return redirect()->route('company/list');
                }
            } else {
                return redirect()->route('company/list');
            }
        }
    }


     public function assign_form(){
        if(Auth::user()->UserType=="2"){
            $forms = DB::table('archtecture_item_forms')
            ->join('users', 'users.id', '=', 'archtecture_item_forms.UserId')
            ->select('archtecture_item_forms.*', 'users.FirstName','users.LastName','users.UserImage')
            ->where('archtecture_item_forms.UserId',Auth::user()->id)->orderBy('archtecture_item_forms.id', 'desc')
            ->get();
            // echo"<pre>";
            // print_r( $forms);
            // die();
            $user = User::where('UserId',Auth::user()->id)->where('UserType',3)->get();
            //  echo"<pre>";
            // print_r( $user);
            // die();
            return view('Company.AssignForm',['formlist'=>$forms,'user'=>$user]);
        }elseif(Auth::user()->UserType=="3"){

        $forms = DB::table('archtecture_item_forms')
        ->join('users', 'users.id', '=', 'archtecture_item_forms.user_id')
        ->select('archtecture_item_forms.*', 'users.FirstName','users.LastName','users.UserImage')
        ->where('archtecture_item_forms.user_id',Auth::user()->id)->orderBy('archtecture_item_forms.id', 'desc')
        ->get();


        return view('Customer.AssignForm',['formlist'=>$forms]);
        }

    }

    public function assign_form_user_store(request $request){
        $data = ArchtectureItemForms::where('id',$request->id)->first();
        $data->user_id = $request->user_id;
        $data->save();
        $request->session()->flash('success','User Assign Success');
        return redirect()->route('assign-form');
    }

    public function deleteCompany(Request $request)
    {


        $userid = $request->companyId;
            $company = Company::where('UserId',$userid)->first();
            AddIronmongery::where('UserId',$userid)->delete();
            BOMSetting::where('UserId',$userid)->delete();

            $cus = Customer::select('id','CstCompanyPhoto')->where('UserId',$userid)->get();
            foreach($cus as $cuss){
                $image_path2 = public_path("CompanyLogo/$cuss->CstCompanyPhoto");  // Value is not URL but directory file path
                if(File::exists($image_path2)) {
                    File::delete($image_path2);
                }
                CustomerContact::where('CustomerId',$cuss->id)->delete();
                ShippingAddress::where('CustomerId',$cuss->id)->delete();
            }
            Customer::where('UserId',$userid)->delete();

            $compCount = Company::select('CompanyPhoto')->where('id',$userid)->count();
            if($compCount > 0){
                $c = Company::select('CompanyPhoto')->where('id',$userid)->first();
                $image_path = public_path("CompanyLogo/$c->CompanyPhoto");  // Value is not URL but directory file path
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            Company::where('UserId',$userid)->delete();
            CompanyQuotationCounter::where('UserId',$userid)->delete();

            $item = Item::select('itemId','SvgImage')->where('UserId',$userid)->get();
            foreach($item as $items){
                $image_path3 = public_path("uploads/files/$items->SvgImage");  // Value is not URL but directory file path
                if(File::exists($image_path3)) {
                    File::delete($image_path3);
                }
                ItemMaster::where('itemID',$items->itemId)->delete();
                QuotationVersionItems::where('itemID',$items->itemId)->delete();
            }
            Item::where('UserId',$userid)->delete();

            $pro = Project::select('id','ProjectImage')->where('CompanyId',$company->id)->get();
            foreach($pro as $pros){
                $image_path4 = public_path("uploads/Project/$pros->ProjectImage");  // Value is not URL but directory file path
                if(File::exists($image_path4)) {
                    File::delete($image_path4);
                }
                $proF = ProjectFiles::select('id','file')->where('projectId',$pros->id)->get();
                foreach($proF as $proFs){
                    $image_path5 = public_path("uploads/Project/$proFs->file");  // Value is not URL but directory file path
                    if(File::exists($image_path5)) {
                        File::delete($image_path5);
                    }
                    ProjectFilesDS::where('projectfileId',$proFs->id)->delete();
                }
                ProjectFiles::where('projectId',$pros->id)->delete();
            }

            $delete_project = Project::where('CompanyId',$company->id)->first();
            if($delete_project){
            ProjectInvitation::where('ProjectId',$delete_project->id)->delete();
            $delete_project->delete();
            }

            $qo = Quotation::select('id')->where('UserId',$userid)->get();
            foreach($qo as $qos){
                QuotationContactInformation::where('QuotationId',$qos->id)->delete();
                QuotationShipToInformation::where('QuotationId',$qos->id)->delete();
                QuotationSiteDeliveryAddress::where('QuotationId',$qos->id)->delete();
                QuotationVersion::where('quotation_id',$qos->id)->delete();
            }
            Quotation::where('UserId',$userid)->delete();

            SelectedColor::where('SelectedUserId',$userid)->delete();
            SelectedIronmongery::where('UserId',$userid)->delete();
            SelectedOption::where('SelectedUserId',$userid)->delete();
            SettingPDF1::where('UserId',$userid)->delete();
            SettingPDF2::where('UserId',$userid)->delete();
            SettingPDFDocument::where('UserId',$userid)->delete();
            SettingPDFfooter::where('UserId',$userid)->delete();
            SettingBOMCost::where('UserId',$userid)->delete();
            User::where('CreatedBy',$userid)->delete();
            User::where('id',$userid)->delete();
            return redirect()->back()->with('success', 'The company deleted successfully!');
    }

    function useremail_check(Request $request){
        if(empty($request->value)){
            $response = [
                'status'=>'error',
                'message'=>'something went wrong!'
            ];
            return response()->json($response, 200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
        $check = User::select('UserEmail','id')->where('UserEmail',$request->value)->first();
        if(empty($check->UserEmail)){
            $response = [
                'status'=>'ok',
                'message'=>'Email not exist.'
            ];
        }else{
            $response = [
                'status'=>'error',
                'message'=>'Email already exist!',
                'UserId' => $check->id,
                'UserEmail' => $check->UserEmail
            ];
        }
        return response()->json($response, 200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function editProfile($id){
        if(Auth::user()->UserType=='2'){
            if(!empty($id)){
                $editdata = Company::join('users','users.id','companies.UserId')->select('users.id as user_id','users.FirstName','users.LastName','users.UserEmail','users.UserJobtitle' ,'users.UserPhone','users.UserImage','companies.*')->where('companies.UserId',$id)->first();
                // dd($editdata);
                return view('Company.editProfile',compact('editdata'));
            } else {
                return redirect()->route('company/edit-profile');
            }
        }
    }

    public function companyStore(request $request)
    {

        if(isset($request->update)){
            $data = Company::where('UserId',$request->update)->first();
            $user = User::where('id',$request->update)->first();
            $flash = "updated";
        }

        // if($request->hasFile('CompanyLogo')){

        //     $this->validate( $request,[
        //         'CompanyLogo' => 'mimes:jpeg,png,jpg|max:1096|image|dimensions:width=200,height=100',
        //     ],[
        //         'CompanyLogo.dimensions' => 'The image must be exactly 200*100 pixcels.'
        //     ]

        //     );


        //     $file = $request->file('CompanyLogo');
        //     $name = time().$file->getClientOriginalName();
        //     $filepath = public_path('CompanyLogo/');

        //     $path = $file;
        //     $type = pathinfo($path, PATHINFO_EXTENSION);
        //     $filedata = file_get_contents($path);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);

        //     $file->move($filepath,$name);

        //     if(isset($request->update)){
        //         File::delete($filepath.$data->CompanyPhoto);
        //     }

        //     $data->CompanyPhoto = $name;
        //     $data->ComplogoBase64 = $base64;
        //     $user->UserImage = $name;
        // }

        if ($request->hasFile('CompanyLogo')) {

            $this->validate($request, [
                'CompanyLogo' => 'required|mimes:jpeg,png,jpg|max:1096|image',
            ]);

            $file = $request->file('CompanyLogo');
            $name = time() . '_' . $file->getClientOriginalName();
            $filepath = public_path('CompanyLogo/');

            // Move the uploaded file to a temporary location
            $tempPath = $filepath . 'temp_' . $name;
            $file->move($filepath, 'temp_' . $name);

            // Load the temporary image and resize it
            list($originalWidth, $originalHeight) = getimagesize($tempPath);
            $src = imagecreatefromstring(file_get_contents($tempPath));

            // Set the desired width and height
            $desiredWidth = 200;
            $desiredHeight = 100;

            // Create a new blank image with the desired width and height
            $dst = imagecreatetruecolor($desiredWidth, $desiredHeight);

            // Handle transparency for PNG and GIF
            if ($file->getClientOriginalExtension() === 'png' || $file->getClientOriginalExtension() === 'gif') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefilledrectangle($dst, 0, 0, $desiredWidth, $desiredHeight, $transparent);
            } else {
                // Fill the background with white for JPEG or other formats
                $background = imagecolorallocate($dst, 255, 255, 255); // white
                imagefilledrectangle($dst, 0, 0, $desiredWidth, $desiredHeight, $background);
            }

            // Resize the original image into the new image
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

            // Save the resized image based on its original type
            $finalPath = $filepath . $name;
            switch ($file->getClientOriginalExtension()) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($dst, $finalPath);
                    break;
                case 'png':
                    imagepng($dst, $finalPath);
                    break;
                case 'gif':
                    imagegif($dst, $finalPath);
                    break;
            }

            // Free up memory
            imagedestroy($src);
            imagedestroy($dst);

            // Remove the temporary image
            unlink($tempPath);

            // Convert the resized image to a Base64 string
            $type = pathinfo($finalPath, PATHINFO_EXTENSION);
            $filedata = file_get_contents($finalPath);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($filedata);

            // If updating, delete the old file
            if (isset($request->update)) {
                File::delete($filepath . $data->CompanyPhoto);
            }

            // Save the new image data to the model
            $data->CompanyPhoto = $name;
            $data->ComplogoBase64 = $base64;
            $user->UserImage = $name;
        }

        $pass = rand(10000,100000);


        $data->CompanyName = $request->CompanyName;
        $data->CompanyWebsite = $request->CompanyWebsite;
        $data->CompanyEmail = $request->CompanyEmail;
        $data->CompanyPhone = $request->CompanyPhone;
        $data->CompanyVatNumber = $request->CompanyVatNumber;
        $data->CompanyAddressLine1 = $request->CompanyAddressLine1;
        $data->CompanyAddressLine2 = $request->CompanyAddressLine2;
        $data->CompanyAddressLine3 = $request->CompanyAddressLine3;
        $data->CompanyCity = $request->CompanyCity;
        $data->CompanyState = $request->CompanyState;
        $data->CompanyCountry = $request->CompanyCountry;
        $data->CompanyPostalCode = $request->CompanyPostalCode;
        $data->CompanyMoreInfo = $request->CompanyMoreInfo;

        $data->CompanyAddressLine1 = $request->CompanyAddressLine1;
        $data->CompanyAddressLine2 = $request->CompanyAddressLine2;
        $data->CompanyAddressLine3 = $request->CompanyAddressLine3;

        $data->Lat = $request->Lat;
        $data->Long = $request->Long;
        $data->save();

        $user->FirstName = $request->FirstName;
        $user->LastName =  $request->LastName;
        $user->UserEmail = $request->UserEmail;
        $user->UserJobtitle = $request->UserJobtitle;
        $user->UserPhone = $request->UserPhone;
        $user->UserType = 2;
        $user->CreatedBy = Auth::user()->id;
        $user->save();

        $request->session()->flash($flash, 'data');
        return redirect()->route('company/profile');
    }

}
