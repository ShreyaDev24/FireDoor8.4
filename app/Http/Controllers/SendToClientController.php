<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Crypt;
use Illuminate\Support\Facades\File;
use Mail;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\QuotationVersion;
use App\Models\CustomerContact;
use App\Models\Project;
use App\Models\SurveyStatus;
use App\Models\SurveyInfo;
use App\Models\SurveyTasks;
use App\Models\SurveyChangerequest;
use App\Models\SurveyAttachment;
use App\Models\Floor;
use App\Models\FloorPlanDoor;
use App\Models\ItemMaster;
use App\Models\QuotationVersionItems;
use App\Models\QuotationContactInformation;
use phpDocumentor\Reflection\Types\Null_;

class SendToClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['quotationApproval','quotaionAccept','quotaionReject']);
    }
    public function sendToClientUrl(Request $request)
    {


        $quotationId = $request->quotationId;
        $currentVersion = $request->currentVersion;
        $CustomerContactId = $request->CustomerContactId;

        $QuotationContactInformation = QuotationContactInformation::where('QuotationId',$quotationId)->first();
        $customer_contact_id = explode(',',$QuotationContactInformation->Contact);

        $q = Quotation::select('QuotationGenerationId','quotTag','SalesContact','ProjectId')->where('id',$quotationId)->first();

        if($q->ProjectId != Null){
            $projectDetails = Project::find($q->ProjectId);
            if($projectDetails && $projectDetails->quotationId != Null && $projectDetails->versionId != Null){
                echo json_encode([
                    'status'=>'error',
                    'message'=>'Quotation is already selected on this project.'
                ]);
                exit;
            }
        }
        $QuotationGenerationId = $q->QuotationGenerationId;
        $SalesCon =  $q->SalesContact;
        // $cc = CustomerContact::join('customers','customers.id','customer_contacts.MainContractorId')->select('customer_contacts.FirstName','customer_contacts.LastName','customer_contacts.ContactEmail')->where('customers.UserId',$CustomerContactId)->first();
        $cc = CustomerContact::where('id',$customer_contact_id[0])->first();

        $fullname = $cc->FirstName.' '.$cc->LastName;

        $com = Company::wherein('UserId',CompanyUsers())->first();


        $QV = QuotationVersion::select('version')->where('id',$currentVersion)->first();
        $version = $QV->version;
        // Check Quotation generated or not
            if($q->quotTag == 0){
                echo json_encode([
                    'status'=>'error',
                    'message'=>'Please generate quotation.'
                ]);
                exit;
            }
        // Check quotation file is exist or not in folder
            $file = public_path().'/quotationFiles'.'/'.$QuotationGenerationId.'_'.$version.'.pdf';
            if(!file_exists($file)){
                echo json_encode([
                    'status'=>'error',
                    'message'=>'Please generate quotation.'
                ]);
                exit;
            }
        if(!empty($QuotationContactInformation->Email)){
            $to = $QuotationContactInformation->Email;
            $subject = "Order process | Quotation $q->QuotationGenerationId";
            $qId = encrypt($quotationId);
            $vId = encrypt($currentVersion);
            $url = route('quotationApproval',[ $qId , $vId ]);
            // http://localhost/GithubSadique/firedoor/public/quotationApproval/eyJpdiI6ImN0T2RXWkVad1V0eGZlMWVzZGgrcEE9PSIsInZhbHVlIjoiWUtwcDBsTExEV0VJRUZSNXZkUnJKUT09IiwibWFjIjoiMmVlMjc1OWZmZDc1MWVlZTA5NzZkMzU4Mjc3NDJmMTA5M2I2NWU1NTQ0ZGFjNmMzMTVmNWJiNzBlMGE1Y2VhMiJ9/eyJpdiI6IlZMajYrN1Y3MkZTaks4Zyt6UGNLM0E9PSIsInZhbHVlIjoicm9Id3RubDNtVzE3VUtNS3hRMVZDUT09IiwibWFjIjoiMjIwZGQ1OWNiOWY3NTY5Zjk4NmJiNmMwY2RlZTdmZjNlZmYxYjhhZjY3ZjU2ZTg3YzA2NTg0ZWQ2MWZiMDk1ZCJ9


            // $headers  = 'MIME-Version: 1.0' . "\r\n";
            // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            // $headers .= "From: firedoor@jfds.co.uk";
            // mail($to,$subject,stripslashes($msg),$headers);
            $emailTo = $QuotationContactInformation->Email;
            $emailFrom = 'noreply@jfds.co.uk';

            if(isset($com->CompanyName)){
                $emailFromName = $com->CompanyName;
            }else{
                $emailFromName = 'noreply@jfds.co.uk';
            }

            $usermname = $cc->FirstName.' '.$cc->LastName;
            $data_set = ['usermname'=>$usermname,'CompanyName'=>$com->CompanyName, 'url'=>$url, 'SalesCon'=>$SalesCon];

            ini_set('display_errors', 1);
            try{
                Mail::send(['html' => 'Mail.SendToClient'], $data_set, function($message) use(&$emailTo, &$subject, &$emailFrom, &$emailFromName) {
                    $message->to($emailTo, $emailTo)->subject($subject);
                    if($emailFrom){
                        $message->from($emailFrom, $emailFromName);
                    }
                });


            } catch (Exception $e) {
                    echo $e->getMessage();
            }

            $q2 = Quotation::find($quotationId);
            $q2->linkStatus = 0;
            $q2->save();

            echo json_encode([
                'status'=>'success',
                'message'=>'Quotation send to these '.$to.' mail id successfully.'
            ]);
            exit;
        }

    }


    public function quotationApproval($quotationId,$versionId)
    {
        $qId = decrypt($quotationId);
        $vId = decrypt($versionId);
        $quotation = Quotation::where('id',$qId)->first();
        $QuotationGenerationId = $quotation->QuotationGenerationId;
        $QV = QuotationVersion::select('version')->where('id',$vId)->first();
        $version = $QV->version;
        $qGId = str_replace('#','%23',$QuotationGenerationId);
        $filename = $qGId.'_'.$version.'.pdf';
        $pdf_file_name =  str_replace("%23", '', $filename);
        return view('sendToClient.quotationApproval',compact('quotation','pdf_file_name','filename','vId'));
    }

    public function quotaionAccept(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        $QuotationStatus = $request->QuotationStatus;
        $PONumber = $request->PONumber;
        $q = Quotation::find($id);
        // dd($q);
        $q->PONumber = $PONumber;
        $q->VersionId = $request->versionId;
        $q->QuotationStatus = $QuotationStatus;
        $q->linkStatus = 1;
        $q->status_accept_reject_at = date('Y-m-d H:i:s');
        if($request->hasFile('file')){
            $file = $request->file('file');
            $ImageName = rando().$file->getClientOriginalName();
            $filepath = public_path('quotationFiles/fileSendByClient/');
            $ImageExtension = $file->getClientOriginalExtension();
            // $ext = pathinfo($filepath.$ImageName , PATHINFO_EXTENSION);

            $file->move($filepath,$ImageName);
            $q->fileByClient = $ImageName;
        }
        if($q->save()){
            $projectId  = $q->ProjectId;

            if(!empty($projectId) && $projectId != NULL ){
                $projectDetails = Project::find($projectId);
                $projectDetails->quotationId = $q->id;
                $projectDetails->versionId = $request->versionId;
                $projectDetails->save();
            }

            // $survey = SurveyInfo::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->get()->first();

            // if(!empty($survey)){
            //     SurveyInfo::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     SurveyTasks::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     SurveyAttachment::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     SurveyChangerequest::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     SurveyChangerequest::where('companyId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     Floor::where('userId' , Auth::user()->id)->where('projectId',$projectId)->delete();
            //     SurveyStatus::where('projectId',$projectId)->delete();
            // }

            $projectDetails = Project::join('quotation_version_items', 'quotation_version_items.version_id', 'project.versionId')->where('project.id',$projectId)->select('project.*','project.id as pId','quotation_version_items.*')->get();
            $SurveyStatusList = SurveyStatus::where('projectId',$projectId)->first();
            if(empty($SurveyStatusList)){
                foreach($projectDetails as $value){
                    $surveyStatus = new SurveyStatus();
                    $surveyStatus->projectId = $projectId;
                    $surveyStatus->quotationId = $value->quotationId;
                    $surveyStatus->versionId = $value->versionId;
                    $surveyStatus->status = 1;
                    $surveyStatus->itemId = $value->itemID;
                    $surveyStatus->itemMasterId = $value->itemmasterID;
                    $surveyStatus->save();

                }
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
                        $floor->projectId = $projectId;
                        $floor->versionId = $request->versionId;
                        $floor->quotationId = $q->id;
                        $floor->save();
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Thank you for accepting quotation!');
    }
    public function quotaionReject(Request $request)
    {
        $id = $request->id;
        $QuotationStatus = $request->QuotationStatus;
        $rejectreason = $request->rejectreason;
        $q = Quotation::find($id);
        $q->QuotationStatus = $QuotationStatus;
        $q->rejectreason = $rejectreason;
        $q->linkStatus = 1;
        $q->status_accept_reject_at = date('Y-m-d H:i:s');
        $q->save();
        return redirect()->back()->with('success', 'You rejected the quotation!');
    }
}
