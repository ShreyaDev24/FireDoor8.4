<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use PdfMerger;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Quotation,Company,Project,SettingPDFfooter,Users,Customer,QuotationContactInformation,CustomerContact,SettingPDF1,SettingPDF2,BOMSetting,SideScreenItem,QuotationShipToInformation,QuotationVersion,Item,BOMDetails,AddIronmongery,LippingSpecies,Option,SettingIntumescentSeals2,IntumescentSealLeafType,ItemMaster,ConfigurableItems,GlassType,OverpanelGlassGlazing,SideScreenItemMaster,SettingPDFDocument,GlazingSystem};
use DB;

class pdf8 implements ShouldQueue
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

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;

        // elevation drawing for side screen
        $elevSideScreenTbl = '';
        $eds = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId])
        ->where(['side_screen_items.VersionId' => $versionID])
        ->select('side_screen_items.*')
        ->groupBy('side_screen_item_master.ScreenID')
        ->get();
        $TotalItems = count($eds->toArray());


        $PageBreakCount = 1;

        foreach ($eds as $tt) {
            // dd($tt);
            $countDoorNumberSide = SideScreenItemMaster::where('ScreenId', $tt->id)->count();
            $DoorNumberS = SideScreenItemMaster::where('ScreenId', $tt->id)->get();
            $doorNoS = '';
            foreach ($DoorNumberS as $bb) {
                $doorNoS .= '<span style="padding-left:5px;">' . $bb->screenNumber . '</span>';
            }
            
            $QuotationGenerationId = null;
            if (!empty($quotaion->QuotationGenerationId)) {
                $QuotationGenerationId = $quotaion->QuotationGenerationId;
            }

            $ProjectName = null;
            if (!empty($project->ProjectName)) {
                $ProjectName = $project->ProjectName;
            }
            
            if (!empty($version)) {
                $version = $version;
            }

            $CompanyAddressLine1 = null;
            if (!empty($comapnyDetail->CompanyAddressLine1)) {
                $CompanyAddressLine1 = $comapnyDetail->CompanyAddressLine1;
            }
            
            $Username = null;
            if (!empty($user->FirstName) && !empty($user->LastName)) {
                $Username = $user->FirstName . ' ' . $user->LastName;
            }

            if (!empty($tt->SvgImage)) {
                $svgFileS = str_contains((string) $tt->SvgImage, '.png') ? URL('/') . '/uploads/files/' . $tt->SvgImage : $tt->SvgImage;
            } else {
                $svgFileS = URL('/') . '/uploads/files/no_image_prod.jpg';
            }
            
            $CstCompanyAddressLine1 = '';
            if (!empty($customerContact)) {
                $customer = Customer::where(['UserId' => $quotaion->MainContractorId])->first();
                $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
            }

            $SalesContact = 'N/A';
            if (!empty($quotaion->SalesContact)) {
                $SalesContact = $quotaion->SalesContact;
            }

            $IsLeafEnabled = 'colspan="2"';
            $elevSideScreenTbl .=
                '
                <div id="headText">
                    <b>Elevation Drawing Side Screen</b>
                </div>
                <div id="main">
                    <div id="section-left">
                        <table id="NoBorder">
                            <tr>
                                <td colspan="2">
                                    <table id="WithBorder" class="tbl1">
                                        <tbody>
                                            <tr>
                                                <td class="marImg" rowspan="2">
                                                    <span>';
            if (!empty($comapnyDetail->ComplogoBase64)) {
                $elevSideScreenTbl .=
                    '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevSideScreenTbl .= Base64Image('defaultImg');
            }
            
            $elevSideScreenTbl .=
                '</span>
                                                </td>
                                                <td class="tbl_color"><span>Ref</span></td>
                                                <td colspan="3"><span>' . $QuotationGenerationId . '</span></td>
                                                <td class="tbl_color"><span>Project</span></td>
                                                <td><span>' . $ProjectName . '</span></td>
                                                <td class="tbl_color"><span>Prepared By</span></td>
                                                <td><span>' . $Username . '</span></td>
                                            </tr>
                                            <tr>
                                                <td class="tbl_color"><span>Revision</span></td>
                                                <td colspan="3"><span>' . $version . '</span></td>
                                                <td class="tbl_color"><span>Customer</span></td>
                                                <td><span>' . $CstCompanyAddressLine1 . '</span></td>
                                                <td class="tbl_color"><span>Sales Contact</span></td>
                                                <td><span>' . $SalesContact . '</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
            $elevSideScreenTbl .= '<tr>';


                $elevSideScreenTbl .= '<td ' . $IsLeafEnabled . '>
                <div class="doorImgBox">
                    <!--<img src="' . URL('/') . '/uploads/files/' . $svgFileS . '" class="doorImg">-->
                    <img src="' . $svgFileS . '" class="doorImg" style="">
                </div>
            </td>
            </tr>';

            $SideScreenChamfered = \Config::get('constants.base64Images.SideScreenChamfered');
            $SideScreenSquare = \Config::get('constants.base64Images.SideScreenSquare');
            $SelectedFrameMaterial = LippingSpecies::where("id", $tt->FrameMaterial)->first();
            // dd($tt->FrameMaterial);
            // dd($SelectedFrameMaterial);
            $FrameMaterials = $SelectedFrameMaterial != null ? $SelectedFrameMaterial->SpeciesName : 'N/A';
            
            if($tt->GlazingBeadShape == 'Square'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenSquare . '" class="" style="">
                </div>

            </td>

            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }

            if($tt->GlazingBeadShape == 'Chamfer'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenChamfered . '" class="" style="">
                </div>

            </td>
            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }


            $elevSideScreenTbl .= '</table>
                    </div>
                    <div id="section-right">
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Screen Type</td>
                                    <td class="dicription_blank">' . $tt->ScreenType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Description</td>
                                    <td class="dicription_blank">Single Screen</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">' . $tt->FireRating . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO WIdth</td>
                                    <td class="dicription_blank">' . $tt->SOWidth . '</td>
                                </tr>';

                    $elevSideScreenTbl .=  '<tr>
                                    <td class="dicription_grey">SO Height</td>
                                    <td class="dicription_blank">' . $tt->SOHeight . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO Depth</td>
                                    <td class="dicription_blank">' . $tt->SODepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Tolerance</td>
                                    <td class="dicription_blank">' . $tt->Tolerance . '</td>
                                </tr>';

                    $elevSideScreenTbl .= '<tr>
                                    <td class="dicription_grey">Frame Thickness</td>
                                    <td class="dicription_blank">' . $tt->FrameThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>';
                $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Material</td>
                                    <td class="dicription_blank">' . lippingName($tt->FrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Finish</td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame WIdth</td>
                                    <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                </tr>';

            $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Height</td>
                                    <td class="dicription_blank">' . $tt->FrameHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Frame Depth</td>
                                    <td class="dicription_blank">' . $tt->FrameDepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Shape</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadShape . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Height</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Glazing Bead Width</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">' .  lippingName($tt->GlazingBeadMaterial). '</td>
                                </tr>';

            $elevSideScreenTbl .=         '
                                <tr>
                                    <td class="dicription_grey">Transom Width</td>
                                    <td class="dicription_blank">' . $tt->TransomWidth1 . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 1 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 2 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 3 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Material</td>
                                    <td class="dicription_blank">' .lippingName($tt->TransomMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 1 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 2 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 3 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Finish </td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                            </tbody>
                        </table>';
            $elevSideScreenTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Lipping And Intumescent</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Type</td>
                                    <td class="dicription_blank">' . $tt->SinglePane  . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing System</td>
                                    <td class="dicription_blank">' . $tt->GlazingSystem . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Liner</td>
                                    <td class="dicription_blank">' . $tt->GlassLiner . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Bottom Thickness</td>
                                    <td class="dicription_blank">' . $tt->SubFrameBottomThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Vision Panel</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Species </td>
                                    <td class="dicription_blank">' . lippingName($tt->SubFrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Acoustics </td>
                                    <td class="dicription_blank">' . $tt->Acoustic . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Special Feature </td>
                                    <td class="dicription_blank">' .$tt->SpecialFeatuers . '</td>
                                </tr>
                            </tbody>
                        </table>';

                        $elevSideScreenTbl .=  '</div></div>
                    <div id="footer">
                        <h3><b>Total Side Screens: ' . $countDoorNumberSide . ',Screen No-' . $doorNoS . '</b></h3>

                    </div>
                ';

            if ($PageBreakCount < $TotalItems) {
                $elevSideScreenTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;
        }

        //  return $elevSideScreenTbl;
        // return view('Company.pdf_files.elevationDrawingSideScreen', compact('elevSideScreenTbl'));
        $pdf8 = PDF::loadView('Company.pdf_files.elevationDrawingSideScreen',['elevSideScreenTbl' => $elevSideScreenTbl]);
        $path8 = public_path() . '/allpdfFile';
        $fileName8 = $id . '8' . '.' . 'pdf';
        $pdf8->save($path8 . '/' . $fileName8);
        // end


        // Document PDF
        $pdf_document = SettingPDFDocument::where('UserId', $id)->first();
        $pdf5 = PDF::loadView('Company.pdf_files.documentpdf', ['pdf_document' => $pdf_document]);
        $path5 = public_path() . '/allpdfFile';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);

    }
}
