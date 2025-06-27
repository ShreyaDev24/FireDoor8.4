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

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FramesTransoms implements FromCollection,WithEvents,WithTitle
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

    // public function collection()
    // {
    //     $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$this->id)->first();

    //     $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('door_frame_construction','items.DoorFrameConstruction','door_frame_construction.DoorFrameConstruction')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->where('door_frame_construction.UserId',Auth::user()->id)->select('item_master.*','items.*','lipping_species.SpeciesName','door_frame_construction.Width','door_frame_construction.Height')->orderBy('items.itemId','ASC')->get();

    //     if(Auth::user()->UserType == 3){
    //         $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
    //         $ids = $users->CreatedBy;
    //     }else{
    //         $ids = Auth::user()->id;
    //     }

    //     $halflapedjoint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();

    //     $k = 1;
    //     $data = [];
    //     foreach($item as $value){
    //         $leg = $value->FrameHeight + $value->Height;
    //         $head = $value->FrameWidth + $value->Width;
    //         $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;

    //         $FrameType = '';
    //         if($value->FrameType == 'Plant_on_Stop'){
    //             $FrameType = $value->PlantonStopHeight;
    //         }elseif($value->FrameType == 'Rebated_Frame'){
    //             $FrameType = $value->RebatedHeight;
    //         }
    //         $stopleg2 = $leg - floatval($FrameType) - 0;
    //         if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
    //             if ($halflapedjoint->Height > 0) {
    //                 $leg = $value->FrameHeight - $value->FrameThickness + $halflapedjoint->Height;
    //             } else {
    //                 $leg = $value->FrameHeight - $value->FrameThickness + $halflapedjoint->Height;
    //             }
    //             $head = $value->FrameWidth - $halflapedjoint->Width;
    //             $stopleg2 = $value->FrameHeight - $value->FrameThickness;
    //             $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
    //         }

    //         $data[] = array(
    //             $value->doorNumber,
    //             $value->plot_ref_no,
    //             $value->certification_no,
    //             $value->FireRating,
    //             $value->LeafThickness,
    //             $value->SpeciesName,
    //             $value->FrameHeight,
    //             $value->FrameWidth,
    //             $value->FrameThickness,
    //             $value->PlantonStopHeight,
    //             $value->PlantonStopWidth,
    //             $value->RebatedWidth,
    //             $value->ScallopedWidth,
    //             $value->ScallopedHeight,
    //             $value->FrameDepth,
    //             '', // Empty column
    //             '', // Empty column
    //             $leg,
    //             $head,
    //             $stopleg2,
    //             $stophead,
    //             '', // Empty column
    //             '', // Empty column
    //             $value->Handing,
    //             str_replace('_', ' ', $value->FrameFinish),
    //             '', // Empty column
    //             '', // Empty column
    //             '', // Empty column
    //             $value->Undercut,
    //              '', // Empty column
    //             '', // Empty column
    //             '', // Empty column
    //         );

    //         $k++;
    //     }

    //     $footData = [
    //         '','','','','','','','','','','','','','','','','','','','','','','','',''
    //     ];

    //     $allData = [$data,$footData];

    //     return collect($allData);
    // }

    // public function headings(): array
    // {
    //     $a = [
    //         'Door Number',
    //         'Plot Number/Ref',
    //         'IFC/Certifire No/Q mark Plug',
    //         'Fire Rating',
    //         'Door Thickness',
    //         'Frame Material',
    //         'O/A Frame H',
    //         'O/A Frame W',
    //         'Frame Thickness',
    //         'Plant on stop thickness',
    //         'Plant on stop Width',
    //         'Rebate Width',
    //         'Scalloped Width',
    //         'Scalloped Depth',
    //         'Frame Depth',
    //         'Thresh Thickness',
    //         'Thresh Material',
    //         'Leg x2',
    //         'Head',
    //         'Stop Leg x 2',
    //         'Stop Head',
    //         'Stop Bottom',
    //         'Bottom- 4 Sided Frame',
    //         'Handing',
    //         'Finish',
    //         'Lock Type 1',
    //         'Lock Type 2',
    //         'Exitex Aluminum Cills',
    //         'Undercut',
    //         'Transom',
    //         'Mullion',
    //         'Notes'
    //     ];

    //     $b = ['Frames and Transoms'];

    //     $d = [$b,$a];
    //     return $d;
    // }
    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $cellRange1 = 'A1:AF1';
    //             $cellRange = 'A2:AF2';
    //             $styleArray = [
    //                 'font' => [
    //                     'bold' => true,
    //                 ],
    //                 'background' => [
    //                     'color'=> '#000000'
    //                 ],
    //                 'alignment' => [
    //                     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 ],
    //                 'borders' => [
    //                     'outline' => [
    //                         'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
    //                         'color' => ['argb' => 'FF0000'],
    //                     ],
    //                 ],

    //             ];
    //             $event->sheet->mergeCells($cellRange1);
    //             $columns = range('Z', 'O'); // 'O' should be replaced with the last column you need

    //             foreach ($columns as $column) {
    //                 $event->sheet->getColumnDimension($column)->setAutoSize(true);
    //             }


    //             $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
    //             $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
    //             $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
    //         },
    //     ];
    // }

    // public function title(): string
    // {
    //     return 'Frames and Transoms';
    // }

        public function collection()
    {
        $quotation = Quotation::select('project.*', 'quotation.*', 'customers.CstCompanyName', 'project.ProjectName as projectname')
            ->leftJoin('project', 'quotation.ProjectId', '=', 'project.id')
            ->leftJoin('customers', 'customers.UserId', 'quotation.MainContractorId')
            ->where('quotation.id', $this->id)->first();

        $item = Item::join('item_master', 'items.itemId', 'item_master.itemID')
            ->leftJoin('door_frame_construction', 'items.DoorFrameConstruction', 'door_frame_construction.DoorFrameConstruction')
            ->leftJoin('lipping_species', 'lipping_species.id', 'items.FrameMaterial')
            ->where('QuotationId', $this->id)
            ->where('VersionId', $this->vid)
            ->where('door_frame_construction.UserId', Auth::user()->id)
            ->select('item_master.*', 'items.*', 'lipping_species.SpeciesName', 'door_frame_construction.Width', 'door_frame_construction.Height')
            ->orderBy('items.itemId', 'ASC')
            ->get();

        $ids = Auth::user()->UserType == 3
            ? User::where('UserType', 3)->where('id', Auth::user()->id)->first()->CreatedBy
            : Auth::user()->id;

        $halflapedjoint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();
        $mitre_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Mitre_Joint')->first();
        $mortice_tenon_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Mortice_&_Tenon_Joint')->first();
        $butt_joint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Butt_Joint')->first();
        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');
        $k = 1;
        $data = [];

        $data[] = array_fill(0, 32, '');
        $data[] = array_merge(['Frames and Transoms'], array_fill(0, 31, ''));

        $data[] = [ // Row 2 - Actual column headings
            'Door Number',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Door Type',
            'Fire Rating',
            'Door Thickness',
            'Frame Material',
            'O/A Frame H',
            'O/A Frame W',
            'Frame Thickness',
            'Plant on stop thickness',
            'Plant on stop Width',
            'Rebate Width',
            'Scalloped Width',
            'Scalloped Depth',
            'Frame Depth',
            'Leg x2',
            'Head',
            'Stop Leg x 2',
            'Stop Head',
            'Stop Bottom',
            'Bottom- 4 Sided Frame',
            'Handing',
            'Finish',
            'Undercut',
            'Transom',
            'Mullion',
            'Notes'
        ];

        foreach ($item as $value) {
            $leg = $value->FrameHeight + $value->Height;
            $head = $value->FrameWidth + $value->Width;
            $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;

            $FrameType = '';
            if ($value->FrameType == 'Plant_on_Stop') {
                $FrameType = $value->PlantonStopHeight;
            } elseif ($value->FrameType == 'Rebated_Frame') {
                $FrameType = $value->RebatedHeight;
            }

            $stopleg2 = $leg - floatval($FrameType);

            if ($value->DoorFrameConstruction == 'Half_Lapped_Joint') {
                $leg = $value->FrameHeight - $value->FrameThickness + $halflapedjoint->Height;
                $head = $value->FrameWidth - $halflapedjoint->Width;
                $stopleg2 = $value->FrameHeight - $value->FrameThickness;
                $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
            }

            $data[] = [
                $value->doorNumber,
                $value->plot_ref_no,
                $value->certification_no,
                $value->DoorType,
                $value->FireRating,
                $value->LeafThickness,
                $value->SpeciesName,
                $value->FrameHeight,
                $value->FrameWidth,
                $value->FrameThickness,
                $value->PlantonStopHeight,
                $value->PlantonStopWidth,
                $value->RebatedWidth,
                $value->ScallopedWidth,
                $value->ScallopedHeight,
                $value->FrameDepth,
                $leg,
                $head,
                $stopleg2,
                $stophead,
                '', // Empty column
                '', // Empty column
                $value->Handing,
                str_replace('_', ' ', $value->FrameFinish),
                $value->Undercut,
                 '', // Empty column
                '', // Empty column
                '', // Empty column
            ];
        }

        // Blank row
        $data[] = array_fill(0, 32, '');
        $data[] = array_fill(0, 32, '');
        $data[] = array_fill(0, 32, '');


        // SCREEN INFO header row (merged A:AF later)
        $data[] = array_merge(['SCREEN INFO'], array_fill(0, 31, ''));

        // Screen info column headers
        $data[] = array_merge([
            'Screen Number',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Fire Rating',
            'Door Thickness',
            'Frame Material',
            'O/A Frame H',
            'O/A Frame W',
            'Frame Thickness',
            'Plant on stop thickness',
            'Plant on stop Width',
            'Rebate Width',
            'Scalloped Width',
            'Scalloped Depth',
            'Frame Depth',
            'Leg x2',
            'Head',
            'Stop Leg x 2',
            'Stop Head',
            'Stop Bottom',
            'Bottom- 4 Sided Frame',
            'Handing',
            'Finish',
            'Undercut',
            'Transom',
            'Mullion',
            'Notes'
        ], array_fill(0, 32 - 18, ''));

         foreach($this->result as $value){
            $screenNumber = $value->screenNumber;
            $ScreenType = $value->ScreenType;
            $FrameMF = lippingName($value->FrameMaterial);
            $Finish = $value->Finish;

            $data[] = [
                    $screenNumber,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $value->FireRating,
                    '',
                    $FrameMF,
                    '',
                    '',
                    '',
                    '',
                    '','','','','','',''.'','','','','','','','','',''
                ];
         }


        // Screen info data
        // $data[] = array_merge([
        //     'SC01-33', '1', 'SCREEN TYPE & DOOR REF 1000X1000X1000',
        //     '0-0/30-0, 30-30, 60-0, 60-60', 'FD30s', '44', 'Redwood, EU',
        //     '1000', '1000', '44', '12', '44', '', '', '', '', '100', ''
        // ], array_fill(0, 32 - 18, ''));

        return collect($data);
    }

    // public function headings(): array
    // {
    //     return [
    //         'Door Number', 'Plot Number/Ref', 'IFC/Certifire No/Q mark Plug',
    //         'Fire Rating', 'Door Thickness', 'Frame Material', 'O/A Frame H', 'O/A Frame W',
    //         'Frame Thickness', 'Plant on stop thickness', 'Plant on stop Width',
    //         'Rebate Width', 'Scalloped Width', 'Scalloped Depth', 'Frame Depth',
    //         'Thresh Thickness', 'Thresh Material', 'Leg x2', 'Head', 'Stop Leg x 2',
    //         'Stop Head', 'Stop Bottom', 'Bottom- 4 Sided Frame', 'Handing', 'Finish',
    //         'Lock Type 1', 'Lock Type 2', 'Exitex Aluminum Cills', 'Undercut',
    //         'Transom', 'Mullion', 'Notes'
    //     ];
    // }

    public function registerEvents(): array
        {
            return [
                \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                    $sheet = $event->sheet;

                    // Auto-size all columns A to AF
                    $col = 'A';
                    while ($col !== 'AB') {
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                        $col++;
                    }

                    $highestRow = $sheet->getHighestRow();

                    // Main title style (green top + bottom borders)
                    $mainTitleStyle = [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '008000'],
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '008000'],
                            ],
                        ],
                    ];

                    // Header row style (red bottom border only)
                    $headerRowStyle = [
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => 'FF0000'],
                            ],
                        ],
                    ];

                    for ($i = 1; $i <= $highestRow; $i++) {
                        $val = trim((string) $sheet->getCell("A{$i}")->getValue());

                        // Apply green top and bottom border to title rows
                        if (in_array($val, ['Door Order Sheet', 'Frames and Transoms', 'SCREEN INFO'])) {
                            $sheet->mergeCells("A{$i}:AB{$i}");
                            $sheet->getStyle("A{$i}:AB{$i}")->applyFromArray($mainTitleStyle);
                        }

                        // Apply red bottom border to heading rows
                        if (in_array($val, ['Door Number', 'SCREEN NO'])) {
                            $sheet->getStyle("A{$i}:AB{$i}")->applyFromArray($headerRowStyle);
                        }
                    }
                },
            ];
        }

    public function title(): string
    {
        return 'Frames and Transoms';
    }

}

