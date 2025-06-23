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
use App\Models\Quotation;
use App\Models\DoorFrameConstruction;
use App\Models\BOMCalculation;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class GlazingBeadsDoors implements FromCollection,WithHeadings,WithEvents,WithTitle
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
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id', $this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.GlazingBeadSpecies')->where('QuotationId', $this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

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
            if ($value->GlazingBeads != '' && $value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 ){
                $VisionPanelWidthNFR = 0;
                $VisionPanelHeightNFR = 0;
                $VisionPanelWidthFD60 = 0;
                $VisionPanelHeightFD60 = 0;
                if(!empty($allSettings['VPBead.NRF'])){
                    $VisionPanelWidthNFR = $allSettings['VPBead.NRF']->Width;
                    $VisionPanelHeightNFR = $allSettings['VPBead.NRF']->Height;
                }
                if(!empty($allSettings['VPBead.FD60'])){
                    $VisionPanelWidthFD60 = $allSettings['VPBead.FD60']->Width;
                    $VisionPanelHeightFD60 = $allSettings['VPBead.FD60']->Height;
                }
                $data[] = array(
                    $value->DoorType,
                    $value->doorNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->GlazingBeads),
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->Leaf1VPWidth + $VisionPanelWidthNFR) : ($value->Leaf1VPWidth + $VisionPanelWidthFD60),
                    $value->VisionPanelQuantity * 4,
                    ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight1 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight1 + $VisionPanelHeightNFR,
                    ($value->VisionPanelQuantity * 2)  + ($value->Leaf2VisionPanelQuantity * 2)
                );

                $k++;
            }
        }

        $footData = [
            '','','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'Door Type',
            'Door Ref',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Timber',
            'Section',
            'Finish on Bead',
            'Saw Cut W',
            'Quantity',
            'Saw Cut L',
            'Quantity'
        ];


        $b = ['Glazing Beads for Doors'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:K1';
                $cellRange = 'A2:K2';
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
                $columns = range('K', 'O'); // 'O' should be replaced with the last column you need

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
        return 'Glazing Beads for Doors';
    }
}
