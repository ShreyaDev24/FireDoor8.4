<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\BOMDetails;
use App\Models\AddIronmongery;
use App\Models\Option;
use App\Models\LippingSpecies;
use App\Models\SettingIntumescentSeals2;

class InvoiceInExcel implements FromCollection,WithHeadings,WithEvents
{
    public function __construct(
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $id,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $vid
    )
    {
    }

    public function collection()
    {
        $quotationId = $this->id;
        $versionId = $this->vid;

        $quotaion = Quotation::where('id',$quotationId)->first();
        $configurationItem = 1;
        if(!empty($quotaion->configurableitems)){
            $configurationItem = $quotaion->configurableitems;
        }
        
        $shows = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
        ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
        ->where('quotation_version_items.version_id',$versionId)->get();
        $SumDoorsetPrice = 0;
        $SumIronmongaryPrice = 0;
        $SumDoorQuantity = 0;
        $j = 1;
        $i = 0;
        foreach($shows as $item){
            $grand_total = BOMDetails::where('itemId',$item->itemId)->sum('grand_total');
            $labour_total = BOMDetails::where('itemId',$item->itemId)->sum('labour_total');
            $DoorsetPrice = $grand_total - $labour_total;
            $IronmongaryPrice = 0;
            if(!empty($item->IronmongeryID)){
                $AI = AddIronmongery::select('discountprice')->where('id',$item->IronmongeryID)->first();
                $IronmongaryPrice = $AI->discountprice;
            }
            
            $totalpriceperdoorset = $DoorsetPrice + $IronmongaryPrice;


            $SumDoorsetPrice += $DoorsetPrice;
            $SumIronmongaryPrice += $IronmongaryPrice;

            $DoorLeafFinish = "N/A";
            if(!empty($item->DoorLeafFinish)){
                $DoorLeafFinish = DoorLeafFinish($configurationItem,$item->DoorLeafFinish);
            }
            
            $DoorLeafFinishColor = '';
            if(!empty($item->DoorLeafFinishColor)){
                $DoorLeafFinishColor = ' + '.$item->DoorLeafFinishColor;
            }

            $finishpluscolor = $DoorLeafFinish . $DoorLeafFinishColor;

            $DoorLeafFacing = "N/A";
            if(!empty($item->DoorLeafFacing)){
                $DoorLeafFacing = DoorLeafFacing($configurationItem,$item->DoorLeafFacing,$item->DoorLeafFacingValue);
            }



            $LippingType = "";
            if(!empty($item->LippingType)){
                $SelectedLippingType = Option::where("configurableitems",$configurationItem)
                ->where("OptionSlug","lipping_type")
                ->where("OptionKey",$item->LippingType)->first();
                if($SelectedLippingType != null){
                    $LippingType = $SelectedLippingType->OptionValue;
                }

                if(!empty($item->LippingSpecies)){
                    $SelectedLippingSpecies = LippingSpecies::find($item->LippingSpecies);
                    if($SelectedLippingSpecies != null){
                        $LippingType .= " - ".$SelectedLippingSpecies->SpeciesName;
                    }
                }
            }

            $Leaf1VisionPanel = "N/A";
            $Leaf2VisionPanel = "N/A";

            if($item->Leaf1VisionPanel == "Yes"){
                $Leaf1VisionPanel = $item->Leaf1VisionPanelShape." (".$item->VisionPanelQuantity.") ".$item->Leaf1VPWidth."x".$item->Leaf1VPHeight1." (".$item->VisionPanelQuantity.") ";
            }

            if($item->Leaf2VisionPanel == "Yes"){
                $Leaf2VisionPanel = $item->Leaf1VisionPanelShape." (".$item->Leaf2VisionPanelQuantity.") ".$item->Leaf2VPWidth."x".$item->Leaf2VPHeight1." (".$item->Leaf2VisionPanelQuantity.") ";
            }

            $GlassTypeForDoorDetailsTable = "N/A";

            if(!empty($item->GlassType)){
                $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem,$item->FireRating,$item->GlassType,$item->GlassThickness);
            }

            $OverpanelForDoorDetailsTable = "N/A";

            if($item->Overpanel == "Yes"){
                $OverpanelForDoorDetailsTable = $item->OPHeigth."x".$item->OPWidth;
            }

            $OPGlassTypeForDoorDetailsTable = "N/A";

            if(!empty($item->OPGlassType)){
                $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem,$item->FireRating,$item->OPGlassType);
            }

            $FrameMaterialForDoorDetailsTable = "N/A";

            if (!empty($item->FrameMaterial) && !in_array($item->FrameMaterial,["MDF","Softwood","Hardwood"])) {
                $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::find($item->FrameMaterial);
                if($SelectedFrameMaterialForDoorDetailsTable != null){
                    $FrameMaterialForDoorDetailsTable = $SelectedFrameMaterialForDoorDetailsTable->SpeciesName;
                }else{
                    $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::where("SpeciesName",$item->FrameMaterial)->where('Status',1)->first();
                    if($SelectedFrameMaterialForDoorDetailsTable != null){
                        $FrameMaterialForDoorDetailsTable = $SelectedFrameMaterialForDoorDetailsTable->SpeciesName;
                    }
                }
            }

            $FrameTypeForDoorDetailsTable = 'N/A';

            if(!empty($item->FrameType)){
                $FrameTypeForDoorDetailsTable = FrameType($configurationItem,$item->FrameType);
            }

            $FrameSizeForDoorDetailsTable = "";
            if(!empty($item->FrameDepth)){
                $FrameSizeForDoorDetailsTable .= $item->FrameDepth."x";
            }
            
            $FrameSizeForDoorDetailsTable .= $item->FrameThickness."mm";

            $FrameFinishForDoorDetailsTable = 'N/A';

            if(!empty($item->FrameFinish)){
                $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem,$item->FrameFinish,$item->FrameFinishColor);
            }

            $ExtLinerForDoorDetailsTable = "None";

            $ExtLinerSizeForDoorDetailsTable = "";

            if(!empty($item->extLinerSize)){
                $ExtLinerSizeForDoorDetailsTable .= $item->extLinerSize.'mm';
            }

            if(!empty($item->ExtLinerThickness)){
                if(!empty($item->extLinerSize)){
                    $ExtLinerSizeForDoorDetailsTable .= "x";
                }
                
                $ExtLinerSizeForDoorDetailsTable .= $item->ExtLinerThickness.'mm';
            }

            $ArchitraveMaterialForDoorDetailsTable = "N/A";
            $ArchitraveTypeForDoorDetailsTable = "N/A";
            $ArchitraveSizeForDoorDetailsTable = "N/A";
            $ArchitraveFinishForDoorDetailsTable = "N/A";

            if($item->Architrave == "Yes"){
                $ArchitraveMaterialForDoorDetailsTable = $item->ArchitraveMaterial;
                $ArchitraveTypeForDoorDetailsTable = $item->ArchitraveType;
                $ArchitraveSizeForDoorDetailsTable = $item->ArchitraveWidth."x".$item->ArchitraveHeight."mm";

                if(!empty($item->ArchitraveFinish)){
                    $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem,$item->ArchitraveFinish,$item->FrameFinishColor);
                }

            }


            if($item->DoorsetType == "DD"){
                $DoorsetType = "Double Doorset";
            }elseif($item->DoorsetType == "SD"){
                $DoorsetType = "Single Doorset";
            }else{
                $DoorsetType = "Leaf and a Half";
            }

            $rWdBRating = '';
            if(!empty($item->rWdBRating)){
                $rWdBRating = $item->rWdBRating;
            }

            $COC = 'None';
            if(!empty($item->COC)){
                $COC = $item->COC;
            }

            $SpecialFeatureRefs = 'None';
            if(!empty($item->SpecialFeatureRefs)){
                $SpecialFeatureRefs = $item->SpecialFeatureRefs;
            }

            $intumescentSeal = '';
            $IntumescentLeapingSealArrangement = $item->IntumescentLeapingSealArrangement;
            if(!empty($IntumescentLeapingSealArrangement)){

                $intum = SettingIntumescentSeals2::select('brand','intumescentSeals')->where('id',$IntumescentLeapingSealArrangement)->first();
                if($intum){

                    $intumescentSeal = $intum->brand.' - '.$intum->intumescentSeals;
                }
            }

            $DoorDescription = '';
            if(!empty($item->DoorsetType)){
                $DoorDescription = DoorDescription($item->DoorsetType);
            }




            // $totalpriceperdoorset = $item->DoorsetPrice + $item->IronmongaryPrice;
            $SumDoorQuantity += $item->DoorQuantity;
            // $SumDoorsetPrice += $item->DoorsetPrice;
            // $SumIronmongaryPrice += $item->IronmongaryPrice;


            $Floor = $item->floor;
            $DoorNumber = $item->doorNumber;
            $DoorQuantity = $item->DoorQuantity;
            $SOHeight = $item->SOHeight;
            $SOWidth = $item->SOWidth;
            $SOWallThick = $item->SOWallThick;
            $DoorType = $item->DoorType;
            $DoorLeafFinish = $finishpluscolor;
            $DoorLeafFacing = $DoorLeafFacing;
            $LippingType = $item->LippingType;
            $LippingSpecies = $item->LippingSpecies;
            $LeafWidth1 = $item->LeafWidth1;
            $LeafWidth2 = $item->LeafWidth2;
            $LeafHeight = $item->LeafHeight;
            $LeafThickness = $item->LeafThickness;
            $Undercut = $item->Undercut;
            $Handing = $item->Handing;
            $OpensInwards = $item->OpensInwards;

            $GlassType = $item->GlassType;

            $FrameMaterial = $item->FrameMaterial;
            $FrameType = $item->FrameType;
            $FrameFinish = $item->FrameFinish;
            $ExtLiner = $item->ExtLiner;
            $ExtLinerSize = $item->ExtLinerSize;

            $IntumescentSeal = $item->IntumescentSeal;

            $ArchitraveMaterial = $item->ArchitraveMaterial;
            $ArchitraveType = $item->ArchitraveType;
            $ArchitraveFinish = $item->ArchitraveFinish;
            $ArchitraveSetQty = $item->ArchitraveSetQty;

            $IronmongerySet = $item->IronmongerySet;
            $rWdBRating = $item->rWdBRating;
            $FireRating = $item->FireRating;
            $COC = $item->COC;
            $SpecialFeatureRefs = $item->SpecialFeatureRefs;
            $DoorsetPrice = round($DoorsetPrice,2);
            $IronmongaryPrice = round($IronmongaryPrice,2);
            $totalpriceperdoorset = round($totalpriceperdoorset,2);

            $data[] = [
                $j,
                $Floor,
                $DoorNumber,
                'description',
                $DoorQuantity,
                $SOHeight,
                $SOWidth,
                $SOWallThick,
                $DoorType,
                $DoorLeafFinish,
                $DoorLeafFacing,
                $LippingType.'-'.$LippingSpecies,
                $LeafWidth1,
                $LeafWidth2,
                $LeafHeight,
                $LeafThickness,
                $Undercut,
                $Handing,
                $OpensInwards,

                'leaf 1 size',
                'leaf 2 size',
                $GlassType,

                'OA screen dim',
                'on screen glass',

                $FrameMaterial,
                $FrameType,
                'frame size',
                $FrameFinish,
                $ExtLiner,
                $ExtLinerSize,

                $IntumescentSeal,

                $ArchitraveMaterial,
                $ArchitraveType,
                'Architrave size',
                $ArchitraveFinish,
                $ArchitraveSetQty,

                $IronmongerySet,
                $rWdBRating,
                $FireRating,
                $COC,
                $SpecialFeatureRefs,
                $DoorsetPrice,
                $IronmongaryPrice,
                $totalpriceperdoorset,


            ];
            $i++;
            $j++;
        }
        
        $Alltotalpriceperdoorset = $SumDoorsetPrice + $SumIronmongaryPrice;
        $footData = [
            '',
            '',
            '',
            '',
            $SumDoorQuantity,
            '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',
            round($SumDoorsetPrice,2),
            round($SumIronmongaryPrice,2),
            round($Alltotalpriceperdoorset,2),
        ];
        $allData = [$data,$footData];

        return collect($allData);
    }
    
    public function headings(): array
    {
        $a = [
            'Line No.',
            'Floor',
            'Door No.',
            'Door Description',
            'Door Qty.',
            'S.O Height',
            'S.O Width',
            'S.O Wall Thick',
            'Door Type',
            'Door Leaf Finish',
            'Door Leaf Facing',
            'Lipping Type - LippingSpecies',
            'Leaf Width 1',
            'Leaf Width 2',
            'Leaf Height',
            'Leaf Thick',
            'Undercut',
            'Handing',
            'Pull Towards',

            'Leaf 1 Size',
            'Leaf 2 Size',
            'Glass Type',

            'OA Screen Dims',
            'Screen Glass',


            'Material',
            'Type',
            'Size',
            'Finish',
            'Ext-Liner',
            'Ext-Liner Size',

            'Intumescent Seal',

            'Material',
            'Type',
            'Size',
            'Finish',
            'Set Qty',

            'Iron. Set',
            'rW Db Rating',
            'Fire Rating',
            'CoC Type',
            'Special Feature Refs',
            'Doorset Price',
            'Ironmongery Price',
            'Total Price per Doorset'

        ];



        $d = $a;
        return $d;







        // $ProjectDetails = [
        //     [
        //       'Project' => 'Project'
        //     ],
        //     [
        //       'Customer' => 'Customer'
        //     ]
        // ];
        // $d = [$ProjectDetails,     $a];



    }
    
    public function registerEvents(): array
    {



        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $versionId = $this->vid;
                $count = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                ->where('quotation_version_items.version_id',$versionId)->count();
                $lastrownumber = $count+2;

                $cellRange = 'A1:AR1'; // All headers
                $lastRow = 'A'.$lastrownumber.':AR'.$lastrownumber;
                // $cellRange->setFontWeight('bold');
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color'=> 'red'
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ]
                ];

                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(60);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($lastRow)->applyFromArray($styleArray);
                // $event->sheet->getDelegate()->getStyle('A2:W2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
