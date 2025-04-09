<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Item;
use App\Models\ItemMaster;
class BomDoorTypeExport implements WithMultipleSheets
{
    use Exportable;

    protected $result;

    protected $ironmongery_info;

    public function __construct(protected $id,protected $vid) {
        // $this->result = BOMCAlculationExport($id,$vid);
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function sheets(): array
    {
        $sheet = [];
        $doorTypes = Item::where(['QuotationId' => $this->id, 'VersionId' => $this->vid])->get();

        foreach ($doorTypes as $door) {
            $itemMasterQty = ItemMaster::where('itemID',$door->itemId)->count();
            if($itemMasterQty != 0){
                $categories = [
                    '' => [
                        'title' => 'Door Type BOM Excel',
                        'headings' => ['Door Ref / Type ', $door->DoorType, '', '', 'Door QTY', $itemMasterQty, '', '']
                    ],
                    'GlazingBeads' => [
                        'title' => 'Glazing Beads',
                        'headings' => ['S.No', 'Door Type', 'Glazing Beads', 'Glazing Bead Species', 'Finish', 'Glazing Bead Dimensions', 'Vision Panel Dimensions', 'Qty Per Door Type', 'Quantity of door types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'Frame' => [
                        'title' => 'Frame',
                        'headings' => ['S.No', 'Door Type', 'Frame Location', 'Frame Material/Finish', 'Frame Size', 'Frame Type', '[Frame Type] Size', 'Qty Per Door Type', 'Quantity of door types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'Accoustics' => [
                        'title' => 'Acoustics',
                        'headings' => ['S.No', 'Door Type', 'rW dB Rating', 'Perimeter Seal 1', 'Perimeter Seal 2', 'Threshold Seal 1', 'Threshold Seal 2', 'Qty Per Door Type', 'Quantity of door types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'Architrave' => [
                        'title' => 'Architrave',
                        'headings' => ['S.No', 'Door Type', 'Architrave Size', 'Architrave Type', 'Architrave Material', 'Architrave Finish', 'Set Qty', 'LM Per Door Type', 'Quantity of door types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'Glass' => [
                        'title' => 'Glass',
                        'headings' => ['S.No', 'Door Type', 'Glass Type', 'Vision Panel Size','','','', 'M2 Per Door Type', 'Quantity of door types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'GlazingSystem' => [
                        'title' => 'Glazing System',
                        'headings' => ['S.No', 'Door Type', 'Glazing System', 'Glazing System Size','','','', 'LM Per Door Type', 'Quantity of Door Types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'IntumescentSeal' => [
                        'title' => 'Intumescent Strip Seals',
                        'headings' => ['S.No', 'Door Type', 'Intumescent Seal Type', 'Intumescent Seal Location', 'Intumescent Seal Colour', 'Brand', 'Intumescent Seal', 'LM Per Door Type', 'Quantity of Door Types', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'LeafSetBesPoke' => [
                        'title' => 'Door Details',
                        'headings' => ['S.No', 'Door Type', 'Door Core', 'Lipping Type', 'Lipping Thickness/Lipping Species', 'Door Leaf Size', 'Door Dimensions Code','', 'Total Quantity', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'MachiningCosts' => [
                        'title' => 'Ironmongery Machining Cost',
                        'headings' => ['S.No', 'Door Type', 'Code/Ironmongery Name', 'MAN HOURS', 'MAN Hour Rate', 'MACHINE HOURS', 'MACHINE Hour Rate', 'Total Quantity', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'GeneralLabourCosts' => [
                        'title' => 'General Labour Costs',
                        'headings' => ['S.No', 'Door Type', 'Labour Element', 'MAN HOURS', 'MAN Hour Rate', 'MACHINE HOURS', 'MACHINE Hour Rate', 'Total Quantity', 'Unit', 'Unit Cost', 'Total Cost', 'Unit Price Sell', 'GT Sell Price', 'Margin']
                    ],
                    'Ironmongery&MachiningCosts' => [
                        'title' => 'Ironmongery',
                        'headings' => ['Ironmongery Set Name', 'Category', 'Code', 'Name', 'Supplier', 'Quantity', 'Price']
                    ]
                ];

                $sections = [];
                foreach ($categories as $category => $config) {
                    $sections[] = [
                        'title' => $config['title'],
                        'headings' => $config['headings'],
                        'data' => getBomDoorTypeDetails($this->id, $this->vid, $door->DoorType, $category)
                    ];
                }

                $sheet[$door->DoorType] = new DoorTypeSheet($sections, $door->DoorType,$this->id);
            }
        }
        
        // dd($sheet);
        return $sheet;
    }
}
