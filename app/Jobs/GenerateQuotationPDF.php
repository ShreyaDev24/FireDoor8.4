<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use setasign\Fpdi\Tcpdf\Fpdi;
use PDF;
use PdfMerger;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Quotation,Company,Project,SettingPDFfooter,Users,Customer,QuotationContactInformation,CustomerContact,SettingPDF1,SettingPDF2,BOMSetting,SideScreenItem,QuotationShipToInformation,QuotationVersion,Item,BOMDetails,AddIronmongery,LippingSpecies,Option,SettingIntumescentSeals2,IntumescentSealLeafType,ItemMaster,ConfigurableItems,GlassType,OverpanelGlassGlazing,SideScreenItemMaster,SettingPDFDocument,GlazingSystem};
use DB;

class GenerateQuotationPDF implements ShouldQueue
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
        // $id = $this->id;
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $quatationId = $this->quatationId;
        $versionID = $this->versionID;
        $quotaion = Quotation::where('id', $quatationId)->first();
        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $id = $users->CreatedBy;
        }else{
            $id = Auth::user()->id;
        }

        $DoorsetPrice = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
        ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
        ->where(['quotation_version_items.version_id'=>$versionID,'items.VersionId'=>$versionID,'items.QuotationId' => $quatationId]);
        $GetIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->get();
        $IronmongeryData = '';
        $PageBreakCount = 1;
        if(!empty($GetIronmongerySet)){
            foreach($GetIronmongerySet as $ironData){
                if (!empty($ironData->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($ironData->IronmongeryID);
                    $IronmongeryData .= '<div id="headText"><b>Ironmongery Set Data</b></div><div>
                    <table id="WithBorder" class="tbl2">'.IronmongerySetData($ironData->IronmongeryID).'</table></div>';
                    if ($PageBreakCount < count($GetIronmongerySet)) {
                        $IronmongeryData .= '<div class="page-break"></div>';
                    }
                    
                    $PageBreakCount++;
                }
            }
        }
        
        $fileName7 = '';
        if($IronmongeryData !== '' && $IronmongeryData !== '0'){
            $pdf7 = PDF::loadView('Company.pdf_files.IronmongeryData', ['IronmongeryData' => $IronmongeryData]);
            $path7 = public_path() . '/allpdfFile';
            $fileName7 = $id . '7' . '.' . 'pdf';
            // return $pdf7->download('IronmongeryData.pdf');
            $pdf7->save($path7 . '/' . $fileName7);
        }

        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';

        $fileName1 = $id . '1' . '.' . 'pdf';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $fileName3 = $id . '3' . '.' . 'pdf';
        $fileName4_2 = $id . '4_2' . '.' . 'pdf';
        $fileName4 = $id . '4' . '.' . 'pdf';
        $fileName6 = $id . '6' . '.' . 'pdf';
        $fileName8 = $id . '8' . '.' . 'pdf';
        $fileName5 = $id . '5' . '.' . 'pdf';

        if($IronmongeryData !== '' && $IronmongeryData !== '0'){
            $pdfFiles = [
                public_path() . '/allpdfFile' . '/' . $fileName1,
                public_path() . '/allpdfFile' . '/' . $fileName2,
                public_path() . '/allpdfFile' . '/' . $fileName3,
                public_path() . '/allpdfFile' . '/' . $fileName4_2,
                public_path() . '/allpdfFile' . '/' . $fileName4,
                public_path() . '/allpdfFile' . '/' . $fileName6,
                public_path() . '/allpdfFile' . '/' . $fileName8,
                public_path() . '/allpdfFile' . '/' . $fileName7,
                public_path() . '/allpdfFile' . '/' . $fileName5,
            ];
        }
        else{
            $pdfFiles = [
                public_path() . '/allpdfFile' . '/' . $fileName1,
                public_path() . '/allpdfFile' . '/' . $fileName2,
                public_path() . '/allpdfFile' . '/' . $fileName3,
                public_path() . '/allpdfFile' . '/' . $fileName4_2,
                public_path() . '/allpdfFile' . '/' . $fileName4,
                public_path() . '/allpdfFile' . '/' . $fileName6,
                public_path() . '/allpdfFile' . '/' . $fileName8,
                public_path() . '/allpdfFile' . '/' . $fileName5,
            ];
        }



            // Merge the PDF files using PDFMerger
            $pdfMerger = PDFMerger::init();
            foreach ($pdfFiles as $pdfFile) {
                $pdfMerger->addPDF($pdfFile, 'all');
            }
            
            $mergedFilePath = public_path() . '/allpdfFile/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';
            $pdfMerger->merge();
            $pdfMerger->save($mergedFilePath);
            $pdfMerger->save(public_path().'/quotationFiles'.'/'.$quotaion->QuotationGenerationId.'_'.$version.'.pdf');

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($mergedFilePath);

            // Set margins
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(true, 10);

            // Disable header and footer completely
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(true);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId, ['adjustPageSize' => true]);

                // Add page number at bottom center
                $pdf->SetY(-20);
                $pdf->Cell(0, 10, 'Page ' . $pageNo . '/' . $pageCount, 0, 0, 'C');
            }

            // Save the final PDF with page numbers
            $outputPath = $PDFfilename;
            $pdf->Output($outputPath, 'F');

            $pdf->Output($quotaion->QuotationGenerationId . '_' . $version . '.pdf', 'D');

            $quo = Quotation::find($quatationId);
            $quo->quotTag = 1;
            $quo->save();

            // unlink($mergedFilePath); (27-11-2024 comment these code bcs it deleted the file to the system and getting 404 not found when send to client the quotations.)

            foreach ($pdfFiles as $unlinkPath) {
                unlink($unlinkPath);
            }
    }
}
