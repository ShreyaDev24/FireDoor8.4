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
use App\Models\DoorFrameConstruction;
use Auth;

class ScreenFrame implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    public function __construct(
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $id,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $vid,
        /**
         * @return \Illuminate\Support\Collection
         */
        protected $result
    )
    {
    }

    public function collection()
    {
        $j = 1;

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $allSettings = DoorFrameConstruction::where('UserId', $ids)->get()->keyBy('DoorFrameConstruction');

        $data = [];
        foreach($this->result as $value){
            $screenNumber = $value->screenNumber;
            $ScreenType = $value->ScreenType;
            $FrameHeight = $value->FrameHeight;
            $FrameWidth = $value->FrameWidth;
            $FrameThickness = $value->FrameThickness;
            $FrameDepth = $value->FrameDepth;
            $poNmber = $value->plot_ref_no;
            $Certificate = $value->certification_no;
            $FrameMF = lippingName($value->FrameMaterial);
            $Finish = $value->Finish;
            $FrameDimensions = [
                'Head' => $FrameHeight . ' x ' .$value->FrameWidth . ' x ' . $value->FrameThickness,
                'Bottom' => $value->FrameWidth . ' x ' . $value->FrameDepth . ' x ' . $value->FrameThickness,
                'Sides' => $value->FrameHeight . ' x ' . $value->FrameDepth . ' x ' . $value->FrameThickness,
            ];
            $Quantities = [
                'Head' => 1,
                'Bottom' => 1,
                'Sides' => 2,
            ];

            $Height = $Width = $TransomWidth = $MullionWidth = 0;
            if(!empty($allSettings['ScreenConstruction.FrameHead'])){
                $Height = $allSettings['ScreenConstruction.FrameHead']->Height;
                $Width = $allSettings['ScreenConstruction.FrameHead']->Width;
            }
            if(!empty($allSettings['ScreenConstruction.Transom'])){
                $TransomWidth = $allSettings['ScreenConstruction.Transom']->Width;
            }
            if(!empty($allSettings['ScreenConstruction.Mullion'])){
                $MullionWidth = $allSettings['ScreenConstruction.Mullion']->Width;
            }

            foreach (['Head'] as $FrameLocation) {
                $Qty = $Quantities[$FrameLocation];
                $screenDim = $FrameDimensions[$FrameLocation];
                $data[] = [
                    $j++,
                    $value->plot_ref_no,
                    $value->certification_no,
                    $screenNumber,
                    $poNmber,
                    $Certificate,
                    $ScreenType,
                    $FrameMF,
                    $Finish,
                    2, // QtyScreenType is constant as 1
                    $Qty,
                    $screenDim,
                ];
            }

            if(!empty($value->TransomQuantity) && ($value->TransomQuantity != 0)){
                $TransomQuantity = $value->TransomQuantity;
                for ($i = 1; $i <= $TransomQuantity; $i++) {
                    $FrameLocation = 'Transom'.$i;
                    $Qty = 1;
                    $TransomThickness ='Transom'.$i.'Thickness';
                    $screenDim = $value->TransomWidth1.' x '.$value->TransomDepth.' x '.$value->$TransomThickness;
                    $FrameMF = lippingName($value->TransomMaterial);

                    $data[] = [
                        $j,
                        $screenNumber,
                        $ScreenType,
                        $FrameLocation,
                        $FrameMF,
                        $Finish,
                        $Qty,
                        1,
                        $screenDim
                    ];
                    $j++;
                }
            }

            if(!empty($value->MullionQuantity) && ($value->MullionQuantity != 0)){
                $MullionQuantity = $value->MullionQuantity;
                for ($i = 1; $i <= $MullionQuantity; $i++) {
                    $FrameLocation = 'Mullion'.$i;
                    $Qty = 1;
                    $MullionThickness ='Mullion'.$i.'Thickness';
                    $screenDim = $value->MullionHeight1.' x '.$value->FrameDepth.' x '.$value->$MullionThickness;
                    $FrameMF = lippingName($value->MullionMaterial);

                    $data[] = [
                        $j,
                        $screenNumber,
                        $ScreenType,
                        $FrameLocation,
                        $FrameMF,
                        $Finish,
                        $Qty,
                        1,
                        $screenDim
                    ];
                    $j++;
                }
            }

            if(!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness)){
                $FrameLocation = 'SubFrame Bottom';
                $FrameMF = lippingName($value->SubFrameMaterial);
                $screenDim = $value->FrameWidth.' x '.$value->FrameDepth.' x '.$value->SubFrameBottomThickness;

                $data[] = [
                    $j,
                    $screenNumber,
                    $ScreenType,
                    $FrameLocation,
                    $FrameMF,
                    $Finish,
                    $Qty,
                    1,
                    $screenDim
                ];
                $j++;
            }

        }

        $footData = [
            '','','','','','','','','','','','','','','','','','','','',''
        ];

        $allData = [$data,$footData];

        return collect($allData);
    }

    public function headings(): array
    {
        $a = [
            'S.No',
            'Screen No',
            'Plot Number/Ref',
            'IFC/Certifire No/Q mark Plug',
            'Screen Type',
            'Frame Material/Finish',
            'Frame Finish',
            'Qty Per Screen Type',
            'Quantity of screen types',
            'Screen Dims ',
            'O/A Frame Height',
            'O/A Frame Width',
            'Frame Thickness',
            'Frame Depth',
            'Leg x 2',
            'Head',
            'Transom',
            'Transom QTY',
            'Mullion',
            'Mullion QTY',
            'Notes',
        ];
        $b = ['Side Screen Frame '];

        $d = [$b,$a];
        return $d;
    }

     public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:U1';
                $cellRange = 'A2:U2';
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
                $columns = range('U', 'O'); // 'O' should be replaced with the last column you need

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
        return 'Screen Frame';
    }
}
