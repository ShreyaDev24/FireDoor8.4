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
use App\Models\QuotationVersion;
use App\Models\BOMCalculation;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class ArchitraveOrderSheet implements FromCollection,WithHeadings,WithEvents,WithTitle
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

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.LippingSpecies')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $k = 1;
        $data = [];
        foreach($item as $value){

            if($quotation->configurableitems == '1' || $quotation->configurableitems == '2' || $quotation->configurableitems == '7' || $quotation->configurableitems == '8'){
                $cutSizeH = ($value->LeafHeight  - $value->LippingThickness - $value->LippingThickness);
                $cutSizeW = ($value->LeafWidth1 - $value->LippingThickness - $value->LippingThickness);
                $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 != null && $value->LeafWidth2 != '' ? ($value->LeafWidth2 - $value->LippingThickness - $value->LippingThickness): '';

                $LFH = ($value->LeafHeight  - $value->LippingThickness + $value->LippingThickness);
                $LFW = ($value->LeafWidth1 - $value->LippingThickness + $value->LippingThickness);
            }else{

                $AdjustmentLeafWidth1 = $value->AdjustmentLeafWidth1 ?? 0;
                $AdjustmentLeafWidth2 = $value->AdjustmentLeafWidth2 ?? 0;
                $AdjustmentLeafHeightNoOP = $value->AdjustmentLeafHeightNoOP ?? 0;

                if($AdjustmentLeafWidth1 == 0){
                    $cutSizeW = $value->LeafWidth1;
                    $LFW = $cutSizeW;
                }else{
                    $cutSizeW = (floatval($value->LeafWidth1 ?? 0) + floatval($AdjustmentLeafWidth1 ?? 0)) - floatval($AdjustmentLeafWidth1 ?? 0) - floatval($value->LippingThickness ?? 0);
                    $LFW = $cutSizeW + $value->LippingThickness;
                }

                if($AdjustmentLeafWidth2 == 0){
                    $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 != null && $value->LeafWidth2 != '' ? ($value->LeafWidth2): '';
                }else{
                    $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 !== null && $value->LeafWidth2 !== ''
                    ? (floatval($value->LeafWidth2) + floatval($AdjustmentLeafWidth2) - floatval($AdjustmentLeafWidth2) - floatval($value->LippingThickness))
                    : '';
                }

                if($AdjustmentLeafHeightNoOP == 0){
                    $cutSizeH = $value->LeafHeight;
                    $LFH = $cutSizeH;
                }else{
                    $cutSizeH = (floatval($value->LeafHeight ?? 0) + floatval($AdjustmentLeafHeightNoOP ?? 0)) - floatval($AdjustmentLeafHeightNoOP ?? 0) - floatval($value->LippingThickness ?? 0);

                    $LFH = $cutSizeH + $value->LippingThickness;
                }
            }
            if($cutSizeW2 <= 0){
                $cutSizeW2 = '';
            }
            $DoorDimensionsCode = '';
            $DoorDimensionsCode2 = '';
            if(isset($quotation->configurableitems) && $quotation->configurableitems == '1'){
                $configurableitems = 'Streboard';
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '2'){
                $configurableitems = 'Halspan';
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '3'){
                $configurableitems = 'Norma';
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '4'){
                $configurableitems = 'Vicaima';
                $DoorDimensionsCode = $value->DoorDimensionsCode . 'x';
                if($value->DoorsetType == 'leaf_and_a_half'){
                    $DoorDimensionsCode2 = $value->DoorDimensionsCode2.'x'.$value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }else if($value->DoorsetType == 'DD'){
                    $DoorDimensionsCode2 = $value->DoorDimensionsCode.'x'.$value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '5'){
                $configurableitems = 'Seadec';
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '6'){
                $configurableitems = 'Deanta';
            }
            elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '7'){
                $configurableitems = 'Flamebreak';
            }
            elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '8'){
                $configurableitems = 'StreDoor';
            }
            elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '9'){
                $configurableitems = 'MMM';
            }

            $data[] = array(
                ($value->DoorQuantity) ? $value->DoorQuantity : 1,
                $value->plot_ref_no,
                $value->certification_no,
                $value->doorNumber,
                $value->DoorType,
                $value->LeafThickness,
                $configurableitems,
                str_replace('_', ' ', $value->DoorLeafFacing),
                $DoorDimensionsCode.$value->LeafWidth1.'x'.$value->LeafHeight.'x'.$value->LeafThickness,
                $DoorDimensionsCode2,
                $cutSizeH,
                $cutSizeW,
                $cutSizeW2,
                $value->LippingThickness,
                $LFW,
                $LFH,
                $value->SpeciesName,
                str_replace('_', ' ', $value->LippingType),
                ''
            );

            $k++;

            if($value->Overpanel == 'Overpanel'){

                if($quotation->configurableitems == '1' || $quotation->configurableitems == '2' || $quotation->configurableitems == '7' || $quotation->configurableitems == '8'){
                    $cutSizeH = $value->OPHeigth - $value->GAP - $value->GAP - $value->OpBeadThickness - $value->OpBeadThickness - $value->LippingThickness - $value->LippingThickness;
                    $cutSizeW = $value->FrameWidth - $value->GAP - $value->GAP - $value->LippingThickness - $value->LippingThickness;
                }else{
                    $cutSizeH = $value->OPHeigth - $value->GAP - $value->GAP - $value->OpBeadThickness - $value->OpBeadThickness - $value->LippingThickness;
                    $cutSizeW = $value->FrameWidth - $value->GAP - $value->GAP - $value->LippingThickness;
                }

                    $data[] = array(
                    ($value->DoorQuantity) ? $value->DoorQuantity : 1,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->doorNumber,
                    $value->DoorType.' OP LEAF SIZE',
                    $value->LeafThickness,
                    $configurableitems,
                    str_replace('_', ' ', $value->DoorLeafFacing),
                    $DoorDimensionsCode.$value->LeafWidth1.'x'.$value->LeafHeight.'x'.$value->LeafThickness,
                    $DoorDimensionsCode2,
                    $cutSizeH,
                    $cutSizeW,
                    $cutSizeW2,
                    $value->LippingThickness,
                    $LFW,
                    $LFH,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->LippingType),
                    ''
                );

                $k++;
            }

        }

        $footData = [
            '','','','','','','','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'Total Doors',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Door Number',
            'Door Type',
            'Door Thickness',
            'Door Mat',
            'Door Finish',
            'PRODUCT CODE LEAF 1 ',
            'PRODUCT CODE LEAF 2',
            'Cut Size H',
            'Cut Size W',
            'Cut Size W2',
            'Lipping Thickness',
            'Lipping Finish W',
            'Lipping Finish H',
            'Lipping Mat',
            'Exposed or Concealed',
            'Notes'
        ];

        $b = ['Architrave Order Sheet'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:S1';
                $cellRange = 'A2:S2';
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
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);

                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Architrave Order Sheet';
    }
}
