<?php

namespace App\Exports;

use App\Models\BOMCalculation;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Sheet as ExcelSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Project-identification block used on the BOM Summary sheet.
 *
 * Rows 1–2 hold the labels and values. Rows 3–4 stay blank, matching
 * Summary.php, so the existing cut-list title and data start at row 5.
 */
class CutListProjectHeader
{
    /** Header rows plus the two blank rows Summary leaves before its content. */
    public const INSERTED_ROWS = 4;

    /** @var array<string, array<string, mixed>> */
    private static array $cache = [];

    /**
     * Insert the BOM Summary header above content that is already on the sheet.
     * Existing cells, merges, and formulas shift down with the insert.
     */
    public static function apply(Worksheet|ExcelSheet $sheet, $quotationId, $versionId): void
    {
        $worksheet = $sheet instanceof Worksheet ? $sheet : $sheet->getDelegate();
        $header = self::values($quotationId, $versionId);

        $worksheet->insertNewRowBefore(1, self::INSERTED_ROWS);

        $worksheet->setCellValue('A1', 'Ref');
        $worksheet->setCellValue('B1', $header['ref']);
        $worksheet->setCellValue('F1', 'Project');
        $worksheet->setCellValue('G1', $header['project']);
        $worksheet->setCellValue('I1', 'Prepared By');
        $worksheet->setCellValue('J1', $header['prepared_by']);

        $worksheet->setCellValue('A2', 'Revision');
        $worksheet->setCellValue('B2', $header['revision']);
        $worksheet->setCellValue('C2', 'Date');
        $worksheet->setCellValue('D2', $header['date']);
        $worksheet->setCellValue('F2', 'Main Contractor');
        $worksheet->setCellValue('G2', $header['main_contractor']);
        $worksheet->setCellValue('I2', 'Sales Contact');
        $worksheet->setCellValue('J2', $header['sales_contact']);

        self::applySummaryStyle($worksheet);

        // Repeat the identification rows on every printed page. Stored on the
        // worksheet, so it stays with the sheet when it is copied or renamed.
        $worksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
    }

    /**
     * Same fields and sources as App\Exports\Summary.
     * Does not call BOMCAlculationExport(), which deletes BOM rows as a side effect.
     *
     * @return array{ref: string, revision: mixed, date: string, project: string, main_contractor: string, prepared_by: string, sales_contact: string}
     */
    public static function values($quotationId, $versionId): array
    {
        $key = $quotationId . ':' . $versionId;
        if (!isset(self::$cache[$key])) {
            self::$cache[$key] = self::load($quotationId, $versionId);
        }

        return self::$cache[$key];
    }

    /**
     * @return array{ref: string, revision: mixed, date: string, project: string, main_contractor: string, prepared_by: string, sales_contact: string}
     */
    private static function load($quotationId, $versionId): array
    {
        $quotation = Quotation::select('project.*', 'quotation.*', 'customers.CstCompanyName', 'project.ProjectName as projectname')
            ->leftjoin('project', 'quotation.ProjectId', '=', 'project.id')
            ->leftjoin('customers', 'customers.UserId', 'quotation.MainContractorId')
            ->where('quotation.id', $quotationId)
            ->first();

        // BOMCAlculationExport resolves the quotation-version id to the numeric
        // version stored on bom_calculations, then reads VersionId from the
        // first matching BOM row. That value is the Summary "Revision" cell.
        $versionNumber = 0;
        if ($versionId) {
            $versionNumber = QuotationVersion::where('id', $versionId)->value('version') ?? 0;
        }

        $bomVersion = BOMCalculation::where('QuotationId', $quotationId)->first();
        $bomQuery = BOMCalculation::join('item_master', 'item_master.itemID', 'bom_calculations.itemId')
            ->where('bom_calculations.QuotationId', $quotationId)
            ->whereNotNull('bom_calculations.itemId')
            ->select('bom_calculations.VersionId')
            ->distinct();

        $unfiltered = $versionNumber == 0
            || $bomVersion === null
            || $bomVersion->VersionId == 0
            || $bomVersion->VersionId === null;

        if (!$unfiltered) {
            $bomQuery->where('bom_calculations.VersionId', $versionNumber);
        }

        $revision = $bomQuery->value('bom_calculations.VersionId');
        if ($revision === null) {
            $revision = '';
        }

        $user = Auth::user();
        $preparedBy = $user ? trim($user->FirstName . ' ' . $user->LastName) : '';

        return [
            'ref' => (string) ($quotation->QuotationGenerationId ?? ''),
            'revision' => $revision,
            'date' => Carbon::now()->format('d-m-Y'),
            'project' => (string) ($quotation->projectname ?? ''),
            'main_contractor' => (string) ($quotation->CstCompanyName ?? ''),
            'prepared_by' => $preparedBy,
            'sales_contact' => (string) ($quotation->SalesContact ?? ''),
        ];
    }

    /**
     * Label styling from Summary::registerEvents (bold, centred).
     * Value cells are left as written so they match the Summary sheet.
     */
    private static function applySummaryStyle(Worksheet $worksheet): void
    {
        $labelStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $worksheet->getStyle('A1:A2')->applyFromArray($labelStyle);
        $worksheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);
        $worksheet->getStyle('C2')->applyFromArray($labelStyle);
        $worksheet->getStyle('F1:F2')->applyFromArray($labelStyle);
        $worksheet->getStyle('I1:I2')->applyFromArray($labelStyle);

        // Summary forces General here so the revision is not shown as a date or currency.
        $worksheet->getStyle('B2')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_GENERAL);

        foreach (range('A', 'J') as $column) {
            $worksheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
