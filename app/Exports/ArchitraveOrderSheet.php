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
use App\Models\LippingSpecies;
use App\Models\SideScreenItemMaster;
use App\Models\DoorFrameConstruction;
use App\Models\User;
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

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        $k = 1;
        $data = [];
        foreach ($item as $value) {
            $FrameHeight = $value->FrameHeight ?? 0;
            $FrameWidth = $value->FrameWidth ?? 0;
            $OPHeight = $value->OPHeigth ?? 0;
            $SL1Width = $value->SL1Width ?? 0;
            $SL2Width = $value->SL2Width ?? 0;

            $ArchitraveWidth = $ArchitraveHeight = 0;
            if(!empty($allSettings['Architrave.NFR'])){
                $ArchitraveWidth = $allSettings['Architrave.NFR']->Width;
                $ArchitraveHeight = $allSettings['Architrave.NFR']->Height;
            }

            if ($value->Architrave == 'Yes') {
                $ls = LippingSpecies::where('id', $value->ArchitraveMaterial)->first();
                $SpeciesName = $ls->SpeciesName ?? '';

                // Calculate total height (2 * FrameHeight + OPHeight if any)
                $totalHeight = ($FrameHeight * 2) + ($OPHeight > 0 ? $OPHeight : 0);

                // Calculate total width (FrameWidth + SL1Width + SL2Width)
                $totalWidth = $FrameWidth + $SL1Width + $SL2Width;

               if($value->ArchitraveSetQty == 1){
                    $lm = $FrameHeight + ($OPHeight * 2) + $FrameWidth + $SL1Width + $SL2Width;
                }else if($value->ArchitraveSetQty == 2){
                    $lm = $FrameHeight + ($OPHeight * 2) + $FrameWidth + $SL1Width + ($SL2Width * 2);
                }

                // Prepare data array
                $data[] = [
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->doorNumber,
                    $value->DoorType,
                    $value->ArchitraveWidth . 'x' . $value->ArchitraveHeight,
                    $value->ArchitraveType,
                    $SpeciesName,
                    $value->ArchitraveFinish,
                    $value->ArchitraveSetQty,
                    (($FrameHeight * 2)+$FrameWidth + $OPHeight +  $SL1Width + $SL2Width)/1000, // LM Per Door Type
                    $totalHeight + $ArchitraveHeight, // Leg x2
                    $totalWidth + $ArchitraveWidth  // Head
                ];
            }
        }


        $footData = [
            '','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            "Plot Number/Ref",
            "IFC/Certifire No/Q mark Plug",
            "Door Number",
            "Door Type",
            "Architrave Size",
            "Architrave Type",
            "Architrave Material",
            "Architrave Finish",
            "Set Qty",
            "LM Per Door Type",
            "Leg x2",
            "Head"
        ];
        $b = ['Architrave '];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:L1';
                $cellRange = 'A2:L2';
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

                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Architrave';
    }
}
