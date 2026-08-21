<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Item;
use App\Models\ItemMaster;

/**
 * Door information export - one sheet per door type, built from the door's own
 * record in the items table (door type, fire rating, doorset type, latch type
 * and the rest of the configuration).
 *
 * This reads the items / item_master tables only. It deliberately does NOT
 * touch bom_calculations, so no cost, sell price or margin figures appear.
 */
class DoorInfoExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * Columns that are internal plumbing or too large to print.
     * Everything else in the items row is shown.
     */
    private const HIDDEN_FIELDS = [
        'itemId', 'QuotationId', 'VersionId', 'CompanyID', 'UserId', 'FolderId',
        'SvgImage', 'created_at', 'updated_at',
        'leaf_price_delta', 'leaf_price_delta_adjust',
    ];

    /**
     * Section order and the fields in each. Any items column not listed here
     * still gets printed, under "Other Information" - nothing is ever dropped.
     */
    private const GROUPS = [
        'Door Information' => [
            'configurableitems', 'LeafConstruction', 'DoorType', 'FireRating', 'DoorsetType',
            'SwingType', 'LatchType', 'LockType', 'Handing', 'OpensInwards', 'COC',
            'DoorQuantity', 'IntumescentLeafType', 'Dropseal', 'FourSidedFrame', 'FrameOnOff',
            'SpecialFeatureRefs',
        ],
        'Structural Opening' => [
            'SOWidth', 'SOHeight', 'SOWallThick', 'Tollerance', 'Undercut', 'FloorFinish',
            'GAP', 'FrameThickness', 'DoorDimensions', 'DoorDimensions2',
            'DoorDimensionsCode', 'DoorDimensionsCode2',
        ],
        'Door Leaf' => [
            'LeafWidth1', 'LeafWidth2', 'LeafHeight', 'AdjustmentLeafWidth1',
            'AdjustmentLeafWidth2', 'AdjustmentLeafHeightNoOP', 'LeafThickness',
            'DoorLeafFacing', 'DoorLeafFacingValue', 'DoorLeafFinish', 'DoorLeafFinishColor',
            'SheenLevel', 'CoreWidth1', 'CoreWidth2', 'CoreHeight',
        ],
        'Hinges' => [
            'hinge1Location', 'hinge2Location', 'hinge3Location', 'hinge4Location',
            'hingeCenterCheck', 'fourthHinges',
        ],
        'Decorative Grooves' => [
            'DecorativeGroves', 'GrooveLocation', 'GrooveWidth', 'GrooveDepth',
            'MaxNumberOfGroove', 'NumberOfGroove', 'NumberOfVerticalGroove',
            'NumberOfHorizontalGroove', 'groovesNumber',
            'DecorativeGrovesLeaf2', 'GrooveLocationLeaf2', 'IsSameAsDecorativeGroves1',
            'GrooveWidthLeaf2', 'GrooveDepthLeaf2', 'MaxNumberOfGrooveLeaf2',
            'NumberOfGrooveLeaf2', 'NumberOfVerticalGrooveLeaf2',
            'NumberOfHorizontalGrooveLeaf2', 'GroovesNumberLeaf2',
        ],
        'Vision Panels' => [
            'Leaf1VisionPanel', 'Leaf1VisionPanelShape', 'VisionPanelQuantity',
            'AreVPsEqualSizesForLeaf1', 'DistanceFromtopOfDoor', 'DistanceFromTheEdgeOfDoor',
            'DistanceBetweenVPs', 'Leaf1VPWidth', 'Leaf1VPHeight1', 'Leaf1VPHeight2',
            'Leaf1VPHeight3', 'Leaf1VPHeight4', 'Leaf1VPHeight5', 'Leaf1VPAreaSizem2',
            'Leaf2VisionPanel', 'sVPSameAsLeaf1', 'Leaf2VisionPanelQuantity',
            'AreVPsEqualSizesForLeaf2', 'DistanceFromTopOfDoorForLeaf2',
            'DistanceFromTheEdgeOfDoorforLeaf2', 'DistanceBetweenVp', 'Leaf2VPWidth',
            'Leaf2VPHeight1', 'Leaf2VPHeight2', 'Leaf2VPHeight3', 'Leaf2VPHeight4',
            'Leaf2VPHeight5',
        ],
        'Glass & Glazing' => [
            'GlassIntegrity', 'GlassType', 'GlassThickness', 'GlazingSystems',
            'GlazingSystemThickness', 'GlazingBeads', 'GlazingBeadsThickness',
            'glazingBeadsWidth', 'glazingBeadsHeight', 'glazingBeadsFixingDetail',
            'GlazingBeadSpecies', 'GlazingTestRef',
        ],
        'Frame' => [
            'FrameMaterial', 'FrameType', 'PlantonStopWidth', 'PlantonStopHeight',
            'RebatedWidth', 'RebatedHeight', 'RebatedHeadDepth', 'RebatedBottomDepth',
            'ScallopedWidth', 'ScallopedHeight', 'standardWidth', 'standardHeight',
            'FrameWidth', 'FrameHeight', 'HeadFrameThickness', 'BottomFrameThickness',
            'FrameDepth', 'FrameFinish', 'FrameFinishColor', 'DoorFrameConstruction',
            'ExtLiner', 'ExtLinerValue', 'extLinerSize', 'ExtLinerThickness',
            'ExtLinerFInish', 'Saddle', 'saddleLocation',
        ],
        'Overpanel' => [
            'Overpanel', 'OPFLTurnOnOff', 'OPLippingThickness', 'OPWidth', 'OPHeigth',
            'OpBeadThickness', 'OpBeadHeight', 'OPTransom', 'TransomThickness',
            'TransomDepth', 'opGlassIntegrity', 'OPGlassType', 'OPGlassThickness',
            'OPGlazingSystems', 'OPGlazingSystemsThickness', 'OPGlazingBeads',
            'OPGlazingBeadsThickness', 'OPGlazingBeadsHeight', 'OPGlazingBeadsFixingDetail',
            'OPGlazingBeadSpecies', 'OPCoreWidth', 'OPCoreHeight', 'OpPanelWidth',
            'OpPanelHeight', 'FanLightWidthGlass', 'FanLightHeightGlass',
        ],
        'Side Light 1' => [
            'SideLight1', 'SL1GlassIntegrity', 'SideLight1GlassType',
            'SideLight1GlassThickness', 'SideLight1GlazingSystems',
            'SideLight1GlazingSystemsThickness', 'BeadingType',
            'SideLight1GlazingBeadsThickness', 'SideLight1GlazingBeadsWidth',
            'SideLight1GlazingBeadsFixingDetail', 'SideLight1FrameThickness',
            'SL1GlazingBeadSpecies', 'SL1Width', 'SL1Height', 'SL1Depth', 'SL1Transom',
            'SL1transomThickness', 'SL1TransomDepth', 'SideLight1GlassWidth',
            'Sidelight1GlassHeight', 'SlBeadThickness', 'SlBeadHeight',
        ],
        'Side Light 2' => [
            'SideLight2', 'DoYouWantToCopySameAsSL1', 'SL2GlassIntegrity',
            'SideLight2GlassType', 'SideLight2GlassThickness', 'SideLight2GlazingSystems',
            'SideLight2GlazingSystemsThickness', 'SideLight2BeadingType',
            'SideLight2GlazingBeadsThickness', 'SideLight2GlazingBeadsWidth',
            'SideLight2GlazingBeadsFixingDetail', 'SideLight2FrameThickness',
            'SideLight2GlazingBeadSpecies', 'SL2Width', 'SL2Height', 'SL2Depth',
            'SL2Transom', 'SL2transomThickness', 'SL2TransomDepth', 'SideLight2GlassWidth',
            'SLtransomHeightFromTop', 'SLtransomThickness',
        ],
        'Lipping' => [
            'LippingType', 'LippingThickness', 'LippingSpecies', 'MeetingStyle',
            'ScallopedLippingThickness', 'FlatLippingThickness', 'RebatedLippingThickness',
        ],
        'Intumescent Seals' => [
            'IntumescentSeal', 'IntumescentSealColor', 'IntumescentSealSize',
            'IntumescentLeapingSealType', 'IntumescentLeapingSealLocation',
            'IntumescentLeapingSealColor', 'IntumescentLeapingSealArrangement',
            'intumescentSealMeetingEdges', 'intumescentSealFireratedTest',
            'IntumescentNotSupplied',
        ],
        'Acoustics' => [
            'Accoustics', 'rWdBRating', 'perimeterSeal1', 'perimeterSeal2',
            'AccousticsMeetingStiles',
        ],
        'Architrave' => [
            'Architrave', 'ArchitraveMaterial', 'ArchitraveType', 'ArchitraveWidth',
            'ArchitraveHeight', 'ArchitraveDepth', 'ArchitraveFinish',
            'ArchitraveFinishColor', 'ArchitraveSetQty',
        ],
        'Ironmongery' => [
            'IronmongerySet', 'IronmongeryID',
        ],
        'Delivery' => [
            'VehicleType', 'DeliveryTime', 'Packaging',
        ],
        'Pricing' => [
            'DoorsetPrice', 'IronmongaryPrice', 'AdjustPrice',
        ],
    ];

    /**
     * Gate field => the fields it controls.
     *
     * When a gate is switched off (No / 0 / blank) its dependent fields are
     * meaningless - they sit at 0 because nothing was ever entered. Printing
     * "Vision Panel: No" followed by a column of zeros is just noise, so the
     * dependants are dropped and only the gate itself is shown.
     */
    private const GATES = [
        'Leaf1VisionPanel' => [
            'Leaf1VisionPanelShape', 'VisionPanelQuantity', 'AreVPsEqualSizesForLeaf1',
            'DistanceFromtopOfDoor', 'DistanceFromTheEdgeOfDoor', 'DistanceBetweenVPs',
            'Leaf1VPWidth', 'Leaf1VPHeight1', 'Leaf1VPHeight2', 'Leaf1VPHeight3',
            'Leaf1VPHeight4', 'Leaf1VPHeight5', 'Leaf1VPAreaSizem2',
        ],
        'Leaf2VisionPanel' => [
            'sVPSameAsLeaf1', 'Leaf2VisionPanelQuantity', 'AreVPsEqualSizesForLeaf2',
            'DistanceFromTopOfDoorForLeaf2', 'DistanceFromTheEdgeOfDoorforLeaf2',
            'DistanceBetweenVp', 'Leaf2VPWidth', 'Leaf2VPHeight1', 'Leaf2VPHeight2',
            'Leaf2VPHeight3', 'Leaf2VPHeight4', 'Leaf2VPHeight5',
        ],
        'DecorativeGroves' => [
            'GrooveLocation', 'GrooveWidth', 'GrooveDepth', 'MaxNumberOfGroove',
            'NumberOfGroove', 'NumberOfVerticalGroove', 'NumberOfHorizontalGroove',
            'groovesNumber',
        ],
        'DecorativeGrovesLeaf2' => [
            'GrooveLocationLeaf2', 'IsSameAsDecorativeGroves1', 'GrooveWidthLeaf2',
            'GrooveDepthLeaf2', 'MaxNumberOfGrooveLeaf2', 'NumberOfGrooveLeaf2',
            'NumberOfVerticalGrooveLeaf2', 'NumberOfHorizontalGrooveLeaf2',
            'GroovesNumberLeaf2',
        ],
        'Overpanel' => [
            'OPFLTurnOnOff', 'OPLippingThickness', 'OPWidth', 'OPHeigth',
            'OpBeadThickness', 'OpBeadHeight', 'OPTransom', 'TransomThickness',
            'TransomDepth', 'opGlassIntegrity', 'OPGlassType', 'OPGlassThickness',
            'OPGlazingSystems', 'OPGlazingSystemsThickness', 'OPGlazingBeads',
            'OPGlazingBeadsThickness', 'OPGlazingBeadsHeight',
            'OPGlazingBeadsFixingDetail', 'OPGlazingBeadSpecies', 'OPCoreWidth',
            'OPCoreHeight', 'OpPanelWidth', 'OpPanelHeight', 'FanLightWidthGlass',
            'FanLightHeightGlass',
        ],
        'SideLight1' => [
            'SL1GlassIntegrity', 'SideLight1GlassType', 'SideLight1GlassThickness',
            'SideLight1GlazingSystems', 'SideLight1GlazingSystemsThickness',
            'BeadingType', 'SideLight1GlazingBeadsThickness',
            'SideLight1GlazingBeadsWidth', 'SideLight1GlazingBeadsFixingDetail',
            'SideLight1FrameThickness', 'SL1GlazingBeadSpecies', 'SL1Width',
            'SL1Height', 'SL1Depth', 'SL1Transom', 'SL1transomThickness',
            'SL1TransomDepth', 'SideLight1GlassWidth', 'Sidelight1GlassHeight',
            'SlBeadThickness', 'SlBeadHeight',
        ],
        'SideLight2' => [
            'DoYouWantToCopySameAsSL1', 'SL2GlassIntegrity', 'SideLight2GlassType',
            'SideLight2GlassThickness', 'SideLight2GlazingSystems',
            'SideLight2GlazingSystemsThickness', 'SideLight2BeadingType',
            'SideLight2GlazingBeadsThickness', 'SideLight2GlazingBeadsWidth',
            'SideLight2GlazingBeadsFixingDetail', 'SideLight2FrameThickness',
            'SideLight2GlazingBeadSpecies', 'SL2Width', 'SL2Height', 'SL2Depth',
            'SL2Transom', 'SL2transomThickness', 'SL2TransomDepth',
            'SideLight2GlassWidth', 'SLtransomHeightFromTop', 'SLtransomThickness',
        ],
        'Accoustics' => [
            'rWdBRating', 'perimeterSeal1', 'perimeterSeal2', 'AccousticsMeetingStiles',
        ],
        'Architrave' => [
            'ArchitraveMaterial', 'ArchitraveType', 'ArchitraveWidth', 'ArchitraveHeight',
            'ArchitraveDepth', 'ArchitraveFinish', 'ArchitraveFinishColor',
            'ArchitraveSetQty',
        ],
        'ExtLiner' => [
            'ExtLinerValue', 'extLinerSize', 'ExtLinerThickness', 'ExtLinerFInish',
        ],
        'Saddle' => ['saddleLocation'],
    ];

    /** Values that mean "switched off" for a gate field. */
    private const OFF_VALUES = ['', '0', 'no', 'off', 'false', 'null', 'none'];

    /** Labels that would not read well from the raw column name. */
    private const LABELS = [
        'configurableitems'  => 'Brand / Configuration',
        'COC'                => 'COC',
        'GAP'                => 'Gap',
        'SOWidth'            => 'SO Width',
        'SOHeight'           => 'SO Height',
        'SOWallThick'        => 'SO Wall Thickness',
        'Tollerance'         => 'Tolerance',
        'Accoustics'         => 'Acoustics',
        'AccousticsMeetingStiles' => 'Acoustics Meeting Stiles',
        'rWdBRating'         => 'rW dB Rating',
        'OPHeigth'           => 'OP Height',
        'ExtLinerFInish'     => 'Ext Liner Finish',
        'IronmongaryPrice'   => 'Ironmongery Price',
        'IronmongeryID'      => 'Ironmongery ID',
        'Leaf1VPAreaSizem2'  => 'Leaf 1 VP Area Size m2',
        'sVPSameAsLeaf1'     => 'VP Same As Leaf 1',
    ];

    /** Brand ids as used across the door forms. */
    private const BRANDS = [
        1 => 'Strebord', 2 => 'Halspan', 3 => 'Norma', 4 => 'Vicaima',
        5 => 'Seadec', 6 => 'Deanta', 7 => 'Flamebreak', 8 => 'Stredor', 9 => 'MMM',
    ];

    /**
     * $onlyItemId limits the workbook to a single door. Keyed on the item id
     * rather than the door type name, which can be renamed at any time.
     */
    public function __construct(protected $id, protected $vid, protected $onlyItemId = null)
    {
    }

    public function sheets(): array
    {
        $sheet = [];
        $doors = Item::where(['QuotationId' => $this->id, 'VersionId' => $this->vid])->get();

        foreach ($doors as $door) {
            if ($this->onlyItemId !== null && (string) $door->itemId !== (string) $this->onlyItemId) {
                continue;
            }

            $sheet[$door->DoorType] = new DoorInfoSheet(
                self::sectionsFor($door),
                (string) $door->DoorType
            );
        }

        return $sheet;
    }

    /**
     * Build the sections for one door: a summary, the list of physical doors,
     * then every populated field from the items row grouped by area.
     */
    public static function sectionsFor($door): array
    {
        $values = $door->getAttributes();
        $qty    = ItemMaster::where('itemID', $door->itemId)->count();

        $sections = [];

        $sections[] = [
            'title'    => 'Summary',
            'headings' => ['Field', 'Value'],
            'data'     => [
                ['Door Type', $door->DoorType],
                ['Fire Rating', $door->FireRating],
                ['Doorset Type', $door->DoorsetType],
                ['Latch Type', $door->LatchType],
                ['Swing Type', $door->SwingType],
                ['Number of Doors', $qty],
            ],
        ];

        $sections[] = self::doorsSection($door);

        $suppressed = self::suppressedFields($values);

        $used = [];
        foreach (self::GROUPS as $title => $fields) {
            $rows = [];
            foreach ($fields as $field) {
                $used[$field] = true;
                if (!array_key_exists($field, $values) || isset($suppressed[$field])) {
                    continue;
                }

                $value = self::formatValue($field, $values[$field]);
                if ($value === '') {
                    continue;
                }

                $rows[] = [self::label($field), $value];
            }

            if ($rows !== []) {
                $sections[] = ['title' => $title, 'headings' => ['Field', 'Value'], 'data' => $rows];
            }
        }

        // Anything the group map does not know about still gets printed.
        $other = [];
        foreach ($values as $field => $raw) {
            if (isset($used[$field]) || isset($suppressed[$field]) || in_array($field, self::HIDDEN_FIELDS, true)) {
                continue;
            }

            $value = self::formatValue($field, $raw);
            if ($value !== '') {
                $other[] = [self::label($field), $value];
            }
        }

        if ($other !== []) {
            $sections[] = ['title' => 'Other Information', 'headings' => ['Field', 'Value'], 'data' => $other];
        }

        return $sections;
    }

    /** The individual doors of this type, from item_master. */
    private static function doorsSection($door): array
    {
        $masters = ItemMaster::where('itemID', $door->itemId)->get();

        $rows = [];
        foreach ($masters as $index => $master) {
            $rows[] = [
                $index + 1,
                $master->doorNumber,
                $master->floor,
                $master->location,
                $master->plot_ref_no,
                $master->certification_no,
                $master->status,
                $master->notes,
            ];
        }

        return [
            'title'    => 'Doors',
            'headings' => ['S.No', 'Door Number', 'Floor', 'Location', 'Plot Ref No', 'Certification No', 'Status', 'Notes'],
            'data'     => $rows,
        ];
    }

    /**
     * Fields to leave out because the feature they belong to is switched off.
     * The gate field itself is always kept, so the sheet still says "No".
     */
    private static function suppressedFields(array $values): array
    {
        $suppressed = [];

        foreach (self::GATES as $gate => $dependents) {
            if (!array_key_exists($gate, $values)) {
                continue;
            }

            if (self::isOff($values[$gate])) {
                foreach ($dependents as $field) {
                    $suppressed[$field] = true;
                }
            }
        }

        return $suppressed;
    }

    private static function isOff($value): bool
    {
        if ($value === null) {
            return true;
        }

        return in_array(strtolower(trim((string) $value)), self::OFF_VALUES, true);
    }

    /** Public entry point for the readable name of an items column. */
    public static function labelFor($field): string
    {
        return self::label($field);
    }

    private static function label($field): string
    {
        if (isset(self::LABELS[$field])) {
            return self::LABELS[$field];
        }

        // OPGlazingBeadSpecies -> OP Glazing Bead Species
        $label = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', (string) $field);
        $label = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1 $2', (string) $label);
        $label = str_replace('_', ' ', (string) $label);

        return ucfirst(trim(preg_replace('/\s+/', ' ', (string) $label)));
    }

    private static function formatValue($field, $raw): string
    {
        if ($raw === null) {
            return '';
        }

        if ($field === 'configurableitems') {
            return self::BRANDS[(int) $raw] ?? (string) $raw;
        }

        $value = trim((string) $raw);

        // Some columns hold the string "null" rather than a real NULL.
        if (strtolower($value) === 'null' || strtolower($value) === 'undefined') {
            return '';
        }

        return $value;
    }
}
