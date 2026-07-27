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
                if($value->DoorsetType == 'leaf_and_a_half'){
                    $DoorDimensionsCode2 = $value->DoorDimensionsCode2.'x'.$value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }else if($value->DoorsetType == 'DD'){
                    $DoorDimensionsCode2 = $value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }
            }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '2'){
                $configurableitems = 'Halspan';
                if($value->DoorsetType == 'leaf_and_a_half'){
                    $DoorDimensionsCode2 = $value->DoorDimensionsCode2.'x'.$value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }else if($value->DoorsetType == 'DD'){
                    $DoorDimensionsCode2 = $value->LeafWidth2.'x'.$value->LeafHeight.'x'.$value->LeafThickness;
                }
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
                'doorset'    => $value->DoorsetType ?? '',
                'doorType'   => trim((string) ($value->DoorType ?? '')),
                'fireRating' => trim((string) ($value->FireRating ?? '')),
                'handling'   => trim((string) ($value->Handing ?? '')),
                'quantity'   => max(1, (int) ($value->DoorQuantity ?? 1)),
                'w1'         => $value->LeafWidth1 ?? '',
                'w2'         => $value->LeafWidth2 ?? '',
                'h'          => $value->LeafHeight ?? '',
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
                    'doorset'    => $value->DoorsetType ?? '',
                    'doorType'   => trim((string) ($value->DoorType ?? '')),
                    'fireRating' => trim((string) ($value->FireRating ?? '')),
                    'handling'   => trim((string) ($value->Handing ?? '')),
                    'quantity'   => max(1, (int) ($value->DoorQuantity ?? 1)),
                    'w1'         => $value->LeafWidth1 ?? '',
                    'w2'         => $value->LeafWidth2 ?? '',
                    'h'          => $value->LeafHeight ?? '',
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

        /*
        |--------------------------------------------------------------------------
        | Build cut-list summary
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | The old grouping key used only product code + lock type. Therefore doors
        | such as A, A-1, A-3 and A 4 were merged when their product code and lock
        | type were the same. The summary then displayed the first Door Type (A),
        | but the count included every merged Door Type.
        |
        | Group by every field displayed in the summary so each summary row has an
        | accurate count: product code, fire rating, door type, handling, lock type.
        */
        $leaf1Counts = [];
        $leaf2Counts = [];
        $codeMeta    = [];
        $groupMeta   = [];
        $lahWidths   = [];

        $makeKey = static function (
            $code,
            $fireRating,
            $doorType,
            $handling,
            $lockType
        ) {
            // json_encode avoids collisions that can happen with a plain delimiter.
            return json_encode([
                trim((string) $code),
                trim((string) $fireRating),
                trim((string) $doorType),
                trim((string) $handling),
                trim((string) $lockType),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        };

        foreach ($data as $rowIndex => $row) {
            $c1 = trim((string) ($row[9] ?? ''));   // PRODUCT CODE LEAF 1
            $c2 = trim((string) ($row[10] ?? ''));  // PRODUCT CODE LEAF 2
            $doorTypeColumn = trim((string) ($row[4] ?? ''));
            $lockType = trim((string) ($rowLockType[$rowIndex] ?? ''));
            $rowInfo  = $rowMeta[$rowIndex] ?? [];

            $isOverpanel = str_contains($doorTypeColumn, 'OP LEAF SIZE');
            $isLeafAndHalf = (($rowInfo['doorset'] ?? '') === 'leaf_and_a_half');

            $doorType   = trim((string) ($rowInfo['doorType'] ?? $doorTypeColumn));
            $fireRating = trim((string) ($rowInfo['fireRating'] ?? ''));
            $handling   = trim((string) ($rowInfo['handling'] ?? ''));
            $quantity   = max(1, (int) ($rowInfo['quantity'] ?? ($row[0] ?? 1)));

            // Leaf 1
            if ($c1 !== '') {
                $key = $makeKey($c1, $fireRating, $doorType, $handling, $lockType);

                // Sum Total Doors, rather than counting every database row as 1.
                $leaf1Counts[$key] = ($leaf1Counts[$key] ?? 0) + $quantity;

                if (!isset($codeMeta[$key])) {
                    $codeMeta[$key] = $parseMeta($c1);
                }

                if (!isset($groupMeta[$key])) {
                    $groupMeta[$key] = [
                        'code'       => $c1,
                        'fireRating' => $fireRating,
                        'doorType'   => $doorType,
                        'handling'   => $handling,
                        'lockType'   => $lockType,
                    ];
                }

                // A leaf-and-a-half doorset has two physical leaves in one summary row.
                // Do not count an overpanel row as leaf 2.
                if ($isLeafAndHalf && !$isOverpanel) {
                    $leaf2Counts[$key] = ($leaf2Counts[$key] ?? 0) + $quantity;
                    $lahWidths[$key] = [
                        'w1' => $rowInfo['w1'] ?? '',
                        'w2' => $rowInfo['w2'] ?? '',
                        'h'  => $rowInfo['h'] ?? '',
                    ];
                }
            }

            // Standard leaf 2. leaf_and_a_half is already handled above.
            if ($c2 !== '' && !$isLeafAndHalf && !$isOverpanel) {
                $key = $makeKey($c2, $fireRating, $doorType, $handling, $lockType);

                $leaf2Counts[$key] = ($leaf2Counts[$key] ?? 0) + $quantity;

                if (!isset($codeMeta[$key])) {
                    $codeMeta[$key] = $parseMeta($c2);
                }

                if (!isset($groupMeta[$key])) {
                    $groupMeta[$key] = [
                        'code'       => $c2,
                        'fireRating' => $fireRating,
                        'doorType'   => $doorType,
                        'handling'   => $handling,
                        'lockType'   => $lockType,
                    ];
                }
            }
        }

        // Union of all summary keys while preserving insertion order.
        $allCodes = array_values(array_unique(array_merge(
            array_keys($leaf1Counts),
            array_keys($leaf2Counts)
        )));

        $summaryRows = [];

        foreach ($allCodes as $key) {
            $l1Count = $leaf1Counts[$key] ?? 0;
            $l2Count = $leaf2Counts[$key] ?? 0;

            $summaryInfo = $groupMeta[$key] ?? [];
            $code        = $summaryInfo['code'] ?? '';
            $fireRating  = $summaryInfo['fireRating'] ?? '';
            $doorSet     = $summaryInfo['doorType'] ?? '';
            $handling    = $summaryInfo['handling'] ?? '';
            $lockType    = $summaryInfo['lockType'] ?? '';

            $meta   = $codeMeta[$key] ?? $parseMeta($code);
            $width  = $meta['width'];
            $height = $meta['height'];

            $leaf1Width = $l1Count > 0 ? $width : '';
            $leaf2Width = $l2Count > 0 ? $width : '';

            // leaf_and_a_half uses the actual two leaf widths.
            if (isset($lahWidths[$key])) {
                $leaf1Width = $l1Count > 0 ? $lahWidths[$key]['w1'] : '';
                $leaf2Width = $l2Count > 0 ? $lahWidths[$key]['w2'] : '';
                $height     = $lahWidths[$key]['h'];
            }

            $gt = $l1Count + $l2Count;

            $summaryRows[] = [
                $code,
                $fireRating,
                $doorSet,
                $handling,
                $leaf1Width,
                $l1Count,
                $leaf2Width,
                $l2Count,
                $height,
                $lockType,
                $gt,
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
