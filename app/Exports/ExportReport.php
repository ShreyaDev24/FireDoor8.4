<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Item;
use App\Models\SideScreenItem;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ExportReport implements FromCollection,WithHeadings,WithEvents,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $result;
    protected $user;

    function __construct($result,$user)
    {
        $this->result = $result;
        $this->user = $user;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->result as $value) {
            // dd($value->currency);
            $quid = $value->QVID ?? 0;
            if($quid > 0){
                $TotalDoorPriceQuery = Item::join('quotation_version_items', 'items.itemId', '=', 'quotation_version_items.itemID')
                ->join('item_master', 'quotation_version_items.itemmasterID', '=', 'item_master.id')
                ->where('quotation_version_items.version_id', $quid)
                ->where('items.QuotationId', $value->QuotationId);
                $TotalExactDoorPrice   = (float) $TotalDoorPriceQuery->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = (float) $TotalDoorPriceQuery->sum('items.IronmongaryPrice'); // ✅ corrected spelling
                // Side screen prices
                $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $value->QuotationId,'side_screen_items.VersionId' => $quid])
                    ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');
            } else {
                $TotalDoorPrice = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->where(['items.QuotationId' => $value->QuotationId]);
                    // dd( $TotalDoorPrice->get());
                $TotalExactDoorPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                // $TotalDoorSetPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = $TotalDoorPrice->sum('items.IronmongaryPrice');

                $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $value->QuotationId])
                ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');
            }

            // Door price & ironmongery price
            $TotalDoorSetPrice = itemAdjustCount($value->QuotationId, $quid);
            $screenDataprice = $SideScreenData->sum('side_screen_items.ScreenPrice');
            $nonConfigDataPrice = nonConfigurableItem($value->QuotationId, $quid, CompanyUsers(), '', true);
            $total_price = $TotalDoorSetPrice +  $TotalIronmongeryPrice + $nonConfigDataPrice + $screenDataprice;
            // Non-configurable items

            $currency = $value->Currency ?? '€';
            // Build data row
            $data[] = [
                $value->QuotationGenerationId,
                $value->FirstName,
                $value->ProjectName,
                $value->QuotationName ?? $value->ProjectName,
                $value->created_at,
                $value->ExpiryDate,
                $value->FollowUpDate,
                $currency.''.$total_price,
                $value->version ?? 1,
                $value->QuotationStatus,
                $this->user->FirstName . ' ' . $this->user->LastName,
                $value->PONumber,
                $currency.''.$TotalDoorSetPrice,
                $currency.''.$screenDataprice,
                $currency.''.$TotalIronmongeryPrice,
                $currency.''.$nonConfigDataPrice,
            ];
        }


        // Footer row
        $footData = array_fill(0, 18, '');
        $data[] = $footData;

        return collect($data);
    }

    public function headings(): array
    {
        $a = [
            'Quotation ID',
            'Contractor',
            'Project',
            'Quotation Name',
            'Date Drafted ',
            'Due Date',
            'Follow up Date ',
            'Quotation Value',
            'Revision',
            'Quotation Status',
            'User',
            'PO Number',
            'Doorset Price',
            'Side Screen Price',
            'Ironmongery Price',
            'Non Configurable Price ',
        ];

        $b = ['Quotation Report'];

        $d = [$b,$a];
        return $d;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange1 = 'A1:P1';
                $cellRange = 'A2:P2';
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
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);

                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray);
            },
        ];
    }

    public function title(): string
    {
        return 'Quotation Report';
    }


}
