<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Models\Item;
use App\Models\Project;
use App\Models\User;
use App\Models\Quotation;
use App\Models\DoorFrameConstruction;
use App\Models\BOMCalculation;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class GlassOrderSheet implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id,$vid,$result;

    function __construct($id,$vid,$result) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
    }

    public function collection()
    {
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        $k = 1;
        $data = [];
        foreach($item as $value){
            if ($value->GlassType != '' && $value->GlassThickness != '' && (($value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0) || ($value->Leaf2VPHeight1 != '' && $value->Leaf2VPHeight1 != 0  && $value->Leaf2VPWidth != '' && $value->Leaf2VPWidth != 0) )){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                if(!empty($allSettings['VisionPanel.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['VisionPanel.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['VisionPanel.NRF']->Height;
                }
                if(!empty($allSettings['VisionPanel.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['VisionPanel.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['VisionPanel.FD60']->Height;
                }

                if($value->DoorsetType == 'SD'){
                    if($value->AreVPsEqualSizesForLeaf1 == 'Yes'){
                        $qty = $value->VisionPanelQuantity;
                    }else{
                        $qty = 1;
                    }
                }else{
                    if($value->AreVPsEqualSizesForLeaf2 == 'Yes'){
                        $qty = $value->Leaf2VisionPanelQuantity + $value->VisionPanelQuantity;
                    }else{
                        if(empty($value->VisionPanelQuantity)){
                            $qty = 1;
                        }else{
                            $qty = (($value->Leaf1VPHeight1)?1:0) + (($value->Leaf2VPHeight1)?1:0);
                        }
                    }
                }

                $data[] = array(
                    $value->DoorType,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->GlassThickness,
                    str_replace('_', ' ', $value->GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight1)?$value->Leaf1VPHeight1:$value->Leaf2VPHeight1) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight1)?$value->Leaf1VPHeight1:$value->Leaf2VPHeight1) + $VisionPanelHeightNFR,

                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ((($value->Leaf1VPWidth)?$value->Leaf1VPWidth:$value->Leaf2VPWidth) + $VisionPanelWidthNFR) : ((($value->Leaf1VPWidth)?$value->Leaf1VPWidth:$value->Leaf2VPWidth) + $VisionPanelWidthFD60),

                    $qty,

                    ($value->Leaf1VPHeight2 || $value->Leaf2VPHeight2) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight2)?$value->Leaf1VPHeight2:$value->Leaf2VPHeight2) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight2)?$value->Leaf1VPHeight2:$value->Leaf2VPHeight2) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight2 || $value->Leaf2VPHeight2) ? (($value->Leaf1VPHeight2)?1:0) + (($value->Leaf2VPHeight2)?1:0) : '',

                    ($value->Leaf1VPHeight3 || $value->Leaf2VPHeight3) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight3)?$value->Leaf1VPHeight3:$value->Leaf2VPHeight3) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight3)?$value->Leaf1VPHeight3:$value->Leaf2VPHeight3) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight3 || $value->Leaf2VPHeight3) ? (($value->Leaf1VPHeight3)?1:0) + (($value->Leaf2VPHeight3)?1:0) : '',

                    ($value->Leaf1VPHeight4 || $value->Leaf2VPHeight4) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight4)?$value->Leaf1VPHeight4:$value->Leaf2VPHeight4) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight4)?$value->Leaf1VPHeight4:$value->Leaf2VPHeight4) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight4 || $value->Leaf2VPHeight4) ? (($value->Leaf1VPHeight4)?1:0) + (($value->Leaf2VPHeight4)?1:0) : '',

                    ($value->Leaf1VPHeight5 || $value->Leaf2VPHeight5) ?(($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? (($value->Leaf1VPHeight5)?$value->Leaf1VPHeight5:$value->Leaf2VPHeight5) + $VisionPanelHeightFD60 : (($value->Leaf1VPHeight5)?$value->Leaf1VPHeight5:$value->Leaf2VPHeight5) + $VisionPanelHeightNFR):'',

                    ($value->Leaf1VPHeight5 || $value->Leaf2VPHeight5) ? (($value->Leaf1VPHeight5)?1:0) + (($value->Leaf2VPHeight5)?1:0) : '',
                );



                $k++;
            }

            if($value->Overpanel == 'Fan_Light'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                if(!empty($allSettings['FanlightSize.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['FanlightSize.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['FanlightSize.NRF']->Height;
                }
                if(!empty($allSettings['FanlightSize.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['FanlightSize.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['FanlightSize.FD60']->Height;
                }

                $data[] = array(
                    $value->DoorType. ' ' .$value->Overpanel,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->OPGlassThickness,

                    str_replace('_', ' ', $value->OPGlassType),

                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? ($value->OPHeigth  - ($value->OpBeadThickness * 2)) + $VisionPanelHeightFD60 : ($value->OPHeigth  - ($value->OpBeadThickness * 2)) + $VisionPanelHeightNFR,

                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? (($value->OPWidth  - ($value->OpBeadThickness * 2))  + $VisionPanelWidthNFR) : (($value->OPWidth  - ($value->OpBeadThickness * 2)) + $VisionPanelWidthFD60),

                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }

            if($value->SideLight1 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                $SLWidthNFR = $SLWidthFD60 = 0;
                if(!empty($allSettings['SideLightFD.FD30'])){
                    $VisionPanelWidthNFR = $allSettings['SideLightFD.FD30']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideLightFD.FD30']->Height;
                    $SLWidthNFR = $value->SL1Width - ($value->SideLight1FrameThickness * 2) + $VisionPanelWidthNFR;
                }
                if(!empty($allSettings['SideLightFD.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideLightFD.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideLightFD.FD60']->Height;
                    $SLWidthFD60 = $value->SL1Width - ($value->SideLight1FrameThickness * 2) + $VisionPanelWidthFD60;
                }



                $data[] = array(
                    $value->DoorType. ' Side Light 1',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SideLight1GlassThickness,

                    str_replace('_', ' ', $value->SideLight1GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ?
                    ($value->SL1Height  - ($value->SideLight1FrameThickness * 2)) + $VisionPanelHeightFD60 :
                    ($value->SL1Height  - ($value->SideLight1FrameThickness * 2)) + $VisionPanelHeightNFR,
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $SLWidthFD60 : $SLWidthNFR,
                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }

            if($value->SideLight2 == 'Yes'){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;

                if(!empty($allSettings['SideLightFD.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['SideLightFD.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['SideLightFD.NRF']->Height;
                    $SLWidth = $value->SL2Width - ($value->SideLight2FrameThickness * 2) + $VisionPanelWidthNFR;
                }
                if(!empty($allSettings['SideLightFD.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['SideLightFD.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['SideLightFD.FD60']->Height;
                    $SLWidth = $value->SL2Width - ($value->SideLight2FrameThickness * 2) + $VisionPanelWidthFD60;
                }

                $data[] = array(
                    $value->DoorType. ' Side Light 2',
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SideLight2GlassThickness,

                    str_replace('_', ' ', $value->SideLight2GlassType),
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ?
                    ($value->SL2Height  - ($value->SideLight2FrameThickness * 2)) + $VisionPanelHeightFD60 :
                    ($value->SL2Height  - ($value->SideLight2FrameThickness * 2)) + $VisionPanelHeightNFR,

                    $SLWidth,

                    1,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                );
            }
        }

        $footData = [
            '','','','','','','','','','','','','','','','',
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'Door Type',
            'Door Number',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Glass Thickness in mm',
            'Glass Type',
            'VP1 H',
            'VP W',
            'QTY',
            'VP2 H',
            'QTY',
            'VP3 H',
            'QTY',
            'VP4 H',
            'QTY',
            'VP5 H',
            'QTY',
        ];

        $b = ['Glass Order Sheet'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:Q1';
                $cellRange = 'A2:Q2';
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'background' => [
                        'color'=> '#000000'
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FF0000'],
                        ],
                    ],

                ];
                $event->sheet->mergeCells($cellRange1);
                $columns = range('Q', 'O'); // 'O' should be replaced with the last column you need

                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }


                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Glass Order Sheet';
    }
}
