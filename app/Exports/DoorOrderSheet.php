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

class DoorOrderSheet implements FromCollection,WithHeadings,WithEvents,WithTitle
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
                $value->IntumescentLeapingSealType,
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
                    $value->IntumescentLeapingSealType,
                    ''
                );

                $k++;
            }

        }

        $parseMeta = function($code) {
    $code = trim((string)$code);
    $meta = ['width' => '', 'height' => ''];
    if ($code === '') return $meta;

    // Pattern: optional "<prefix>x", then "<width>x<height>x<thickness>"
    if (preg_match('/^(?:[^x]+x)?(\d+(?:\.\d+)?)x(\d+(?:\.\d+)?)x(\d+(?:\.\d+)?)/', $code, $m)) {
        $meta['width']  = $m[1] ?? '';
        $meta['height'] = $m[2] ?? '';
    }
    return $meta;
};

// Count occurrences for each product code in col 8 (Leaf 1) and col 9 (Leaf 2)
$leaf1Counts = [];
$leaf2Counts = [];
$codeMeta    = []; // store width/height per code

foreach ($data as $row) {
    $c1 = $row[8] ?? '';  // PRODUCT CODE LEAF 1
    $c2 = $row[9] ?? '';  // PRODUCT CODE LEAF 2
    $cutW2 = $row[12] ?? ''; // Cut Size W2 (column M, 0-based index 12)

    // ✅ Leaf 1 logic
    if ($c1) {
        $leaf1Counts[$c1] = ($leaf1Counts[$c1] ?? 0) + 1;
        if (!isset($codeMeta[$c1])) $codeMeta[$c1] = $parseMeta($c1);
    }

    // ✅ Leaf 2 logic: count if PRODUCT CODE LEAF 2 exists OR Cut Size W2 is numeric/non-empty
    if ($c2 || (is_numeric($cutW2) && $cutW2 > 0)) {
        // Use Leaf 2’s code if available; else reuse Leaf 1’s code for dimension grouping
        $targetCode = $c2 ?: $c1;
        $leaf2Counts[$targetCode] = ($leaf2Counts[$targetCode] ?? 0) + 1;
        if (!isset($codeMeta[$targetCode])) $codeMeta[$targetCode] = $parseMeta($targetCode);
    }
}

// Union of all codes (preserve a stable order)
$allCodes = array_values(array_unique(array_merge(array_keys($leaf1Counts), array_keys($leaf2Counts))));

// Build summary rows
$summaryRows = [];
foreach ($allCodes as $code) {
    $l1Count = $leaf1Counts[$code] ?? 0;
    $l2Count = $leaf2Counts[$code] ?? 0;

    $meta   = $codeMeta[$code] ?? $parseMeta($code);
    $width  = $meta['width'];
    $height = $meta['height'];

    // Show width under the side that actually has a count
    $leaf1Width = $l1Count > 0 ? $width : '';
    $leaf2Width = $l2Count > 0 ? $width : '';

    $gt = $l1Count + $l2Count;

    $summaryRows[] = [
        $code,           // Summary (Product Code)
        $leaf1Width, $l1Count,   // Leaf 1 | Count
        $leaf2Width, $l2Count,   // Leaf 2 | Count
        $height,                // Height
        $gt                     // GT
    ];
}

// Header row for summary
$summaryHeader = ['Summary','Leaf 1','Count','Leaf 2','Count','Height','GT'];

// Merge: main rows + blank separator + summary header + summary rows
$merged = array_merge($data, [array_fill(0, 20, '')], [$summaryHeader], $summaryRows);

return collect($merged);


        // $footData = [
        //     '','','','','','','','','','','','','','','','','','','',''
        // ];

        // $allData = [$data,$footData];

        // return collect($allData);
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
            'Intumescent Seal Type',
            'Notes'
        ];

        $b = ['Door Order Sheet'];

        $d = [$b,$a];
        return $d;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // ----------------------------
                // 🔹 Existing header styling
                // ----------------------------
                $cellRange1 = 'A1:T1'; // main merged header
                $cellRange2 = 'A2:T2'; // column headings row

                $styleArray = [
                    'font' => ['bold' => true],
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

                // Merge and style top header
                $event->sheet->mergeCells($cellRange1);
                $event->sheet->getStyle($cellRange1)->applyFromArray($styleArray);
                $event->sheet->getStyle($cellRange2)->applyFromArray($styleArray);
                $event->sheet->getStyle($cellRange2)->getAlignment()->setWrapText(true);

                // Auto size all columns A–T
                foreach (range('A', 'T') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // ----------------------------
                // 🔹 New Summary Header Styling
                // ----------------------------
                $highestRow = $event->sheet->getHighestRow(); // last row on the sheet

                // Find the row number of "Summary" header (by searching for text)
                $summaryHeaderRow = null;
                foreach (range(1, $highestRow) as $r) {
                    $cellVal = $event->sheet->getCell('A' . $r)->getValue();
                    if (trim((string)$cellVal) === 'Summary') {
                        $summaryHeaderRow = $r;
                        break;
                    }
                }

                if ($summaryHeaderRow) {
                    // Make the summary header bold, centered, with light gray fill
                    $event->sheet->getStyle('A' . $summaryHeaderRow . ':G' . $summaryHeaderRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFD9D9D9'], // light gray background
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                    ]);
                }
            },
        ];
    }


    public function title(): string
    {
        return 'Door Order Sheet';
    }
}
