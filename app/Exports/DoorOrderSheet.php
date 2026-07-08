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
    protected $id,$vid,$result,$section;

    function __construct($id,$vid,$result,$section = null) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = $result;
        $this->section = $section;
    }

    public function collection()
    {
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$this->id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.LippingSpecies')->where('QuotationId',$this->id)->where('VersionId',$this->vid)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $k = 1;
        $data = [];
        $rowLockType = []; // lock type aligned 1:1 with $data rows (used to group the cut list summary)
        $rowMeta = []; // raw doorset info aligned 1:1 with $data rows (leaf_and_a_half needs the real leaf widths)
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
                str_replace('_', ' ', $value->DoorLeafFinish),
                $DoorDimensionsCode.$value->LeafWidth1.'x'.$value->LeafHeight.'x'.$value->LeafThickness,
                $DoorDimensionsCode2,
                IronmongerySetName($value->IronmongeryID),
                $cutSizeH,
                $cutSizeW,
                $cutSizeW2,
                $value->LippingThickness,
                $LFW,
                $LFH,
                $value->SpeciesName,
                str_replace('_', ' ', $value->LippingType),
                $value->IntumescentLeapingSealType,
                $value->rWdBRating,
                ''
            );
            $rowLockType[] = $value->LockType ?? '';
            $rowMeta[] = [
                'doorset' => $value->DoorsetType ?? '',
                'w1'      => $value->LeafWidth1 ?? '',
                'w2'      => $value->LeafWidth2 ?? '',
                'h'       => $value->LeafHeight ?? '',
            ];

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
                    str_replace('_', ' ', $value->DoorLeafFinish),
                    $DoorDimensionsCode.$value->LeafWidth1.'x'.$value->LeafHeight.'x'.$value->LeafThickness,
                    $DoorDimensionsCode2,
                    IronmongerySetName($value->IronmongeryID),
                    $cutSizeH,
                    $cutSizeW,
                    $cutSizeW2,
                    $value->LippingThickness,
                    $LFW,
                    $LFH,
                    $value->SpeciesName,
                    str_replace('_', ' ', $value->LippingType),
                    $value->IntumescentLeapingSealType,
                    $value->rWdBRating,
                    ''
                );
                $rowLockType[] = $value->LockType ?? '';
                $rowMeta[] = [
                    'doorset' => $value->DoorsetType ?? '',
                    'w1'      => $value->LeafWidth1 ?? '',
                    'w2'      => $value->LeafWidth2 ?? '',
                    'h'       => $value->LeafHeight ?? '',
                ];

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
        $codeFireRating = []; // store fire rating per code
        $codeHandling = []; // store handling per code
        $codeDoorSet = []; // store door set per code
        $codeLockType = []; // store lock type per code
        $lahWidths = []; // leaf_and_a_half: real leaf1/leaf2 widths + height per summary key

        foreach ($item as $itemValue) {
            // dd($item);
            $c1 = $itemValue->DoorDimensionsCode.'x'.$itemValue->LeafWidth1.'x'.$itemValue->LeafHeight.'x'.$itemValue->LeafThickness;
            if ($c1 && !isset($codeFireRating[$c1])) {
                $codeFireRating[$c1] = $itemValue->FireRating ?? '';
            }
            if ($c1 && !isset($codeHandling[$c1])) {
                $codeHandling[$c1] = $itemValue->Handing ?? '';
            }
            if ($c1 && !isset($codeDoorSet[$c1])) {
                $codeDoorSet[$c1] = $itemValue->DoorType ?? '';
            }
            if ($c1 && !isset($codeLockType[$c1])) {
                $codeLockType[$c1] = $itemValue->LockType ?? '';
            }
        }

        // Lock type is a (non-mandatory) grouping dimension for the cut list summary.
        // Build a composite key "<product code>||<lock type>" so doors that share dimensions
        // but differ in lock type get their own summary row. Empty lock types group together,
        // preserving the previous behaviour when lock type isn't populated.
        $codeKeyLock = []; // composite key => lock type (for splitting the key back out)
        $makeKey = function($code, $lockType) {
            return $code . '||' . $lockType;
        };

        foreach ($data as $rowIndex => $row) {
            $c1 = $row[9] ?? '';  // PRODUCT CODE LEAF 1
            $c2 = $row[10] ?? '';  // PRODUCT CODE LEAF 2
            $opLeafExist = $row[4] ?? '';  // PRODUCT CODE LEAF 2
            $cutW2 = $row[12] ?? ''; // Cut Size W2 (column M, 0-based index 12)
            $lockType = $rowLockType[$rowIndex] ?? ''; // lock type for this row
            $rowInfo  = $rowMeta[$rowIndex] ?? []; // raw doorset info for this row
            $isLeafAndHalf = (($rowInfo['doorset'] ?? '') === 'leaf_and_a_half');

            // ✅ Leaf 1 logic
            if ($c1) {
                $key = $makeKey($c1, $lockType);
                $leaf1Counts[$key] = ($leaf1Counts[$key] ?? 0) + 1;
                if (!isset($codeMeta[$key])) $codeMeta[$key] = $parseMeta($c1);
                $codeKeyLock[$key] = $lockType;

                // leaf_and_a_half: leaf 2 is the same physical doorset as leaf 1 (2 widths,
                // 1 height) so it shares the leaf 1 summary row and carries its own width.
                // Overpanel (OP LEAF SIZE) rows are excluded, as in the generic leaf 2 logic.
                if ($isLeafAndHalf && !str_contains($opLeafExist, 'OP LEAF SIZE')) {
                    $leaf2Counts[$key] = ($leaf2Counts[$key] ?? 0) + 1;
                    $lahWidths[$key] = [
                        'w1' => $rowInfo['w1'] ?? '',
                        'w2' => $rowInfo['w2'] ?? '',
                        'h'  => $rowInfo['h'] ?? '',
                    ];
                }
            }

            // ✅ Leaf 2 logic: count if PRODUCT CODE LEAF 2 exists OR Cut Size W2 is numeric/non-empty
            // Leaf 2 should count ONLY when PRODUCT CODE LEAF 2 exists
            // (leaf_and_a_half is handled above so its leaf 2 stays on the leaf 1 row)
            if ($c2 && !$isLeafAndHalf) {
                if(!str_contains($opLeafExist, 'OP LEAF SIZE')){
                    $key = $makeKey($c2, $lockType);
                    $leaf2Counts[$key] = ($leaf2Counts[$key] ?? 0) + 1;
                    if (!isset($codeMeta[$key])) $codeMeta[$key] = $parseMeta($c2);
                    $codeKeyLock[$key] = $lockType;
                }
            }
        }

        // Union of all composite keys (preserve a stable order)
        $allCodes = array_values(array_unique(array_merge(array_keys($leaf1Counts), array_keys($leaf2Counts))));

        // Build summary rows
        $summaryRows = [];
        foreach ($allCodes as $key) {
            $l1Count = $leaf1Counts[$key] ?? 0;
            $l2Count = $leaf2Counts[$key] ?? 0;

            // Split the composite key back into product code + lock type
            $lockType = $codeKeyLock[$key] ?? '';
            $code     = ($pos = strrpos($key, '||')) !== false ? substr($key, 0, $pos) : $key;

            $meta   = $codeMeta[$key] ?? $parseMeta($code);
            $width  = $meta['width'];
            $height = $meta['height'];

            $matchedItem = $item->first(function ($itemValue) use ($code, $lockType) {
                if (($itemValue->LockType ?? '') !== $lockType) {
                    return false;
                }

                $leaf1Code = $itemValue->DoorDimensionsCode . 'x' . $itemValue->LeafWidth1 . 'x' . $itemValue->LeafHeight . 'x' . $itemValue->LeafThickness;
                $leaf1CodeAlt = $itemValue->LeafWidth1 . 'x' . $itemValue->LeafHeight . 'x' . $itemValue->LeafThickness;

                $leaf2Code = '';
                $leaf2CodeAlt = '';

                if (!empty($itemValue->LeafWidth2)) {
                    $leaf2Code = $itemValue->DoorDimensionsCode2 . 'x' . $itemValue->LeafWidth2 . 'x' . $itemValue->LeafHeight . 'x' . $itemValue->LeafThickness;
                    $leaf2CodeAlt = $itemValue->LeafWidth2 . 'x' . $itemValue->LeafHeight . 'x' . $itemValue->LeafThickness;
                }

                return in_array($code, [$leaf1Code, $leaf1CodeAlt, $leaf2Code, $leaf2CodeAlt]);
            });

            $fireRating = $matchedItem->FireRating ?? '';
            $handling   = $matchedItem->Handing ?? '';
            $doorSet    = $matchedItem->DoorType ?? '';
            // $lockType already resolved from the composite key above

            $leaf1Width = $l1Count > 0 ? $width : '';
            $leaf2Width = $l2Count > 0 ? $width : '';

            // leaf_and_a_half: leaf 1 and leaf 2 have different widths but share one height,
            // so use the real leaf widths instead of the single parsed product-code width.
            if (isset($lahWidths[$key])) {
                $leaf1Width = $l1Count > 0 ? $lahWidths[$key]['w1'] : '';
                $leaf2Width = $l2Count > 0 ? $lahWidths[$key]['w2'] : '';
                $height     = $lahWidths[$key]['h'];
            }

            $gt = $l1Count + $l2Count;

            $summaryRows[] = [
                $code,           // Summary (Product Code)
                $fireRating,
                $doorSet,
                $handling,
                $leaf1Width,
                $l1Count,
                $leaf2Width,
                $l2Count,
                $height,
                $lockType,
                $gt,                    // GT
            ];
        }

        // Header row for summary
        $summaryHeader = ['Summary','Fire Rating','Doorset Type','Handling','Leaf 1','Count','Leaf 2','Count','Height','Lock Type','GT'];

        if($this->section != 'Summary'){
            // Merge: main rows + blank separator + summary header + summary rows
            $merged = array_merge($data, [array_fill(0, 20, '')], [$summaryHeader], $summaryRows);
        }else{
            // Merge: main rows + blank separator + summary header + summary rows
            $merged = array_merge([$summaryHeader], $summaryRows);
        }



        return collect($merged);
    }

    public function headings(): array
    {
        $a = [];
        if($this->section != 'Summary'){
            $a = [
                'Total Doors',
                'Plot Number/Ref',
                'IFC/Certifire No/Q mark Plug',
                'Door Number',
                'Door Type',
                'Door Thickness',
                'Door Mat',
                'Door Leaf Facing',
                'Door Leaf Finish',
                'PRODUCT CODE LEAF 1 ',
                'PRODUCT CODE LEAF 2',
                'Ironmongery Ref',
                'Cut Size H',
                'Cut Size W',
                'Cut Size W2',
                'Lipping Thickness',
                'Lipping Finish W',
                'Lipping Finish H',
                'Lipping Mat',
                'Exposed or Concealed',
                'Intumescent Seal Type',
                'DB Rating',
                'Notes'
            ];
        }

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
                if($this->section != 'Summary'){
                    $cellRange1 = 'A1:W1'; // main merged header
                    $cellRange2 = 'A2:W2'; // column headings row
                }else{
                    $cellRange1 = 'A1:H1'; // main merged header
                    $cellRange2 = 'A2:H2'; // column headings row
                }

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

                // Auto size all columns A–V
                foreach (range('A', 'W') as $col) {
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
                    $event->sheet->getStyle('A' . $summaryHeaderRow . ':K' . $summaryHeaderRow)->applyFromArray([
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
