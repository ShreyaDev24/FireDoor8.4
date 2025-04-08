<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ScheduleOrder2 implements FromCollection,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $item = Item::orderBy('itemId','desc')->limit(5)->groupBy('DoorType')->get();
        $j = 1;
        $i = 0;
        foreach($item as $items){
            $DoorType = $item[$i]->DoorType;
            $count = Item::where('DoorType',$DoorType)->count();

            // $totalpriceperdoorset = $item[$i]->DoorsetPrice + $item[$i]->IronmongaryPrice;
            // $DoorQuantity += $item[$i]->DoorQuantity;
            // $DoorsetPrice += $item[$i]->DoorsetPrice;
            // $IronmongaryPrice += $item[$i]->IronmongaryPrice;



            $Floor = $item[$i]->Floor;
            $DoorNumber = $item[$i]->DoorNumber;
            $DoorQuantity = $item[$i]->DoorQuantity;
            $SOHeight = $item[$i]->SOHeight;
            $SOWidth = $item[$i]->SOWidth;
            $SOWallThick = $item[$i]->SOWallThick;

            $DoorLeafFinish = $item[$i]->DoorLeafFinish;
            $DoorLeafFacing = $item[$i]->DoorLeafFacing;
            $LippingType = $item[$i]->LippingType;
            $LippingSpecies = $item[$i]->LippingSpecies;
            $LeafWidth1 = $item[$i]->LeafWidth1;
            $LeafWidth2 = $item[$i]->LeafWidth2;
            $LeafHeight = $item[$i]->LeafHeight;
            $LeafThickness = $item[$i]->LeafThickness;
            $Undercut = $item[$i]->Undercut;
            $Handing = $item[$i]->Handing;
            $OpensInwards = $item[$i]->OpensInwards;
            $GlassType = $item[$i]->GlassType;

            $FrameMaterial = $item[$i]->FrameMaterial;
            $FrameType = $item[$i]->FrameType;
            $FrameFinish = $item[$i]->FrameFinish;
            $ExtLiner = $item[$i]->ExtLiner;
            $ExtLinerSize = $item[$i]->ExtLinerSize;

            $IntumescentSeal = $item[$i]->IntumescentSeal;

            $ArchitraveMaterial = $item[$i]->ArchitraveMaterial;
            $ArchitraveType = $item[$i]->ArchitraveType;
            $ArchitraveFinish = $item[$i]->ArchitraveFinish;
            $ArchitraveSetQty = $item[$i]->ArchitraveSetQty;

            $IronmongerySet = $item[$i]->IronmongerySet;
            $rWdBRating = $item[$i]->rWdBRating;
            $FireRating = $item[$i]->FireRating;
            $COC = $item[$i]->COC;
            $SpecialFeatureRefs = $item[$i]->SpecialFeatureRefs;
            $DoorsetPrice = $item[$i]->DoorsetPrice;
            $IronmongaryPrice = $item[$i]->IronmongaryPrice;
            $totalpriceperdoorset = $item[$i]->totalpriceperdoorset;

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

                $count
            ];
            $i++;
            $j++;
        }
        
        return collect($data);
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
            'Total Price per Doorset',

            'Count Duplicate Entry'

        ];

        return $a;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event): void {
                $cellRange = 'A1:AS1'; // All headers
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
                // $event->sheet->getDelegate()->getStyle('A2:W2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
