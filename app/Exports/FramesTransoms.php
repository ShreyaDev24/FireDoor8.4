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
use Carbon\Carbon;
use App\Models\Company;
use App\Models\SideScreenItemMaster;
use Auth;

class FramesTransoms implements FromCollection,WithHeadings,WithEvents,WithTitle
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

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('door_frame_construction','items.DoorFrameConstruction','door_frame_construction.DoorFrameConstruction')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->where('door_frame_construction.UserId',Auth::user()->id)->select('item_master.*','items.*','lipping_species.SpeciesName','door_frame_construction.Width','door_frame_construction.Height')->orderBy('items.itemId','ASC')->get();

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $halflapedjoint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();
        $mitre_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Mitre_Joint')->first();
        $mortice_tenon_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Mortice_&_Tenon_Joint')->first();
        $butt_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Butt_Joint')->first();
        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');
        $k = 1;
        $data = [];
        foreach($item as $value){
            $leg = $value->FrameHeight + $value->Height;
            $head = $value->FrameWidth + $value->Width;
            $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;

            $FrameType = '';
            if($value->FrameType == 'Plant_on_Stop'){
                $FrameType = $value->PlantonStopHeight;
            }elseif($value->FrameType == 'Rebated_Frame'){
                $FrameType = $value->RebatedHeight;
            }
            $stopleg2 = $leg - floatval($FrameType) - 0;

            $Height = 0;
            $Width = 0;
            if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                $Height = $halflapedjoint->Height ?? 0;
                $Width = $halflapedjoint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                $Height = $mitre_joint->Height ?? 0;
                $Width = $mitre_joint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                $Height = $mortice_tenon_joint->Height ?? 0;
                $Width = $mortice_tenon_joint->Width ?? 0;
            }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                $Height = $butt_joint->Height ?? 0;
                $Width = $butt_joint->Width ?? 0;
            }

            $leg = $value->FrameHeight - $value->FrameThickness + $Height;
            $head = $value->FrameWidth - $Width;
            $stopleg2 = $value->FrameHeight - $value->FrameThickness;
            $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;

            if($value->FrameType == 'Plant_on_Stop'){
                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if(!empty($allSettings['PlantOn.HalfLipped'])){
                        $stophead += $allSettings['PlantOn.HalfLipped']->Width;
                        $stopleg2 += $allSettings['PlantOn.HalfLipped']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                    if(!empty($allSettings['PlantOn.Mitre'])){
                        $stophead += $allSettings['PlantOn.Mitre']->Width;
                        $stopleg2 += $allSettings['PlantOn.Mitre']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                    if(!empty($allSettings['PlantOn.Mortice1'])){
                        $stophead += $allSettings['PlantOn.Mortice1']->Width;
                        $stopleg2 += $allSettings['PlantOn.Mortice1']->Height;
                    }
                }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                    if(!empty($allSettings['PlantOn.Butt'])){
                        $stophead += $allSettings['PlantOn.Butt']->Width;
                        $stopleg2 += $allSettings['PlantOn.Butt']->Height;
                    }
                }
            }


            $data[] = array(
                $value->doorNumber,
                $value->FireRating,
                $value->LeafThickness,
                $value->SpeciesName,
                $value->FrameHeight,
                $value->FrameWidth,
                $value->FrameThickness,
                $FrameType,
                $value->FrameDepth,
                '', // Empty column
                '', // Empty column
                $leg,
                $head,
                $stopleg2,
                $stophead,
                '', // Empty column
                $value->Handing,
                str_replace('_', ' ', $value->FrameFinish),
                '', // Empty column
                '', // Empty column
                '', // Empty column
                $value->Undercut,
                $value->SpecialFeatureRefs,
                '', // Empty column
            );

            $k++;
        }

        $footData = [
            '','','','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'Door Number',
            'Fire Rating',
            'Door Thickness',
            'Frame Material',
            'O/A Frame H',
            'O/A Frame W',
            'Frame Thickness',
            'Plant on Stop',
            'Frame Depth',
            'Thresh Thickness',
            'Thresh Material',
            'Leg',
            'Head',
            'Stop Leg x 2',
            'Stop Head',
            'Stop Bottom',
            'Handing',
            'Finish',
            'Lock Type 1',
            'Lock Type 2',
            'Exitex Aluminum Cills',
            'Undercut',
            'Notes'
        ];

        $b = ['Frames and Transoms'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:W1';
                $cellRange = 'A2:W2';
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
                $columns = range('W', 'O'); // 'O' should be replaced with the last column you need

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
        return 'Frames and Transoms';
    }
}
