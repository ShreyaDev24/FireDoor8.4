<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Quotation,Company,Project,SettingPDFfooter,Users,Customer,QuotationContactInformation,CustomerContact,SettingPDF1};
use DB;

class pdf1 implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $quatationId, public $versionID)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $quatationId = $this->quatationId;
        $versionID = $this->versionID;

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $id = $users->CreatedBy;
        }else{
            $id = Auth::user()->id;
        }
        
        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $contractorName = DB::table('users')->where(['id' => $quotaion->MainContractorId, 'UserType' => 5 ])->value('FirstName');
        $contractorName = $contractorName ?: '';

        // $configurationItem = 1;
        $configurationItem = $quotaion->configurableitems;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();
        
        $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();

        $SalesContact = 'N/A';
        if (!empty($quotaion->SalesContact)) {
            $SalesContact = $quotaion->SalesContact;
        }

        // PDF 1 ( Introduction PDF )
        if (!empty($quotaion->MainContractorId)) {
            $customerContact = Users::where('id', $quotaion->MainContractorId)->first();
        } else {
            $customerContact = '';
        }

        $customer = '';
        $CstCompanyAddressLine1 = '';
        if (!empty($customerContact)) {
            $customer = Customer::where(['UserId' => $quotaion->MainContractorId])->first();
            $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
        }

        $quotaion_contact_info = QuotationContactInformation::where('QuotationId',$quatationId)->first();
        if($quotaion_contact_info->Contact){
            $contactid = explode(',',(string) $quotaion_contact_info->Contact);
            $contact_persion = CustomerContact::where('id',$contactid[0])->first();
            $contactfirstandlastname = $contact_persion->FirstName . ' ' . $contact_persion->LastName;
        }
        else{
            $contactfirstandlastname = '';
        }


        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $pdf1 = SettingPDF1::where('UserId', $id)->first();
        $pdf = PDF::loadView('Company.pdf_files.introductionpdf', ['pdf1' => $pdf1, 'pdf_footer' => $pdf_footer, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'customerContact' => $customerContact, 'project' => $project, 'user' => $user, 'customer' => $customer, 'contactfirstandlastname' => $contactfirstandlastname, 'contractorName' => $contractorName]);
        // return $pdf->download('file.pdf');
        $path1 = public_path() . '/allpdfFile';
        $fileName1 = $id . '1' . '.' . 'pdf';
        $pdf->save($path1 . '/' . $fileName1);
    }
}
