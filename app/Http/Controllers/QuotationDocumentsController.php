<?php

namespace App\Http\Controllers;

use App\Exports\DoorInfoExport;
use App\Models\DoorChangeLog;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\SelectedOptionLog;
use App\Models\User;
use App\Services\DoorOptionsChecker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

/**
 * Quotation documents folder.
 *
 * Every quotation gets a folder named after the quotation, and inside it one
 * Excel workbook per door type, holding that door's information from the items
 * table. Nothing here writes to any table - it only reads what the door form
 * already saved, so the existing quotation / door / BOM flow is untouched.
 *
 * Files are (re)built from live data whenever they are requested and then
 * mirrored to disk, so a file on disk can never be out of date with the quote.
 */
class QuotationDocumentsController extends Controller
{
    /** Folder under public/ that holds every quotation folder. */
    public const ROOT = 'quotationDocuments';

    /**
     * Folder listing: the quotation folder and one row per door type.
     */
    public function index($quotationId, $versionId)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        $project = Project::find($quotation->ProjectId);
        $version = QuotationVersion::find($versionId);

        $folderPath = $this->versionFolderPath($quotation, $version);
        $this->makeFolder($folderPath);

        $doorTypes = $this->doorTypes($quotationId, $versionId);

        return view('DoorSchedule.QuotationDocuments', [
            'quotation'   => $quotation,
            'project'     => $project,
            'version'     => $version,
            'versionId'   => $versionId,
            'doorTypes'   => $doorTypes,
            'folderName'  => $this->quotationFolderName($quotation),
            'versionName' => $this->versionFolderName($version),
            'totalDoors'  => array_sum(array_column($doorTypes, 'qty')),
        ]);
    }

    /**
     * On-screen view of one door type - the same sections that go into its
     * Excel file, rendered as HTML tables.
     */
    public function preview($quotationId, $versionId, Request $request)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        $door = $this->resolveDoor($quotationId, $versionId, $request);
        if (empty($door)) {
            return redirect()->back()->with('error', 'Door not found in this version.');
        }

        $doorType = (string) $door->DoorType;
        $qty      = ItemMaster::where('itemID', $door->itemId)->count();

        return view('DoorSchedule.QuotationDocumentPreview', [
            'quotation'  => $quotation,
            'versionId'  => $versionId,
            'doorType'   => $doorType,
            'itemId'     => $door->itemId,
            'qty'        => $qty,
            'sections'   => DoorInfoExport::sectionsFor($door),
            'fileName'   => $this->doorFileName($doorType),
            'folderName' => $this->quotationFolderName($quotation),
        ]);
    }

    /**
     * Change history for this quotation version - what a door's value was
     * before an edit and what it became. Optionally narrowed to one door type.
     */
    public function history($quotationId, $versionId, Request $request)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        // Filter on the item id, never the door type name. The name recorded on
        // a log row is the one from before that edit, so a renamed door would
        // never match its own history if we filtered by name.
        $door     = $this->resolveDoor($quotationId, $versionId, $request);
        $itemId   = $door->itemId ?? null;
        $doorType = $door->DoorType ?? '';

        $query = DoorChangeLog::where('quotation_id', $quotationId)
            ->where('version_id', $versionId);

        if ($itemId !== null) {
            $query->where('item_id', $itemId);
        }

        $logs = $query->orderByDesc('id')->limit(1000)->get();

        $userNames = User::whereIn('id', $logs->pluck('changed_by')->filter()->unique())
            ->get()
            ->mapWithKeys(fn ($u): array => [$u->id => trim($u->FirstName . ' ' . $u->LastName)]);

        return view('DoorSchedule.QuotationDocumentHistory', [
            'quotation'  => $quotation,
            'versionId'  => $versionId,
            'doorType'   => $doorType,
            'itemId'     => $itemId,
            'logs'       => $logs,
            'userNames'  => $userNames,
            'doorTypes'  => $this->doorTypes($quotationId, $versionId),
            'folderName' => $this->quotationFolderName($quotation),
        ]);
    }

    /**
     * Every door value that is no longer available in Selected Options, so a
     * deleted glass type (or similar) cannot go unnoticed across the quotation.
     */
    public function optionsCheck($quotationId, $versionId)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        $checker = new DoorOptionsChecker($quotation->UserId);
        $result  = $checker->check($quotationId, $versionId);

        // Records anything added to / removed from Selected Options since the
        // last look, so there is a running list as well as the live check.
        $checker->reconcileLog();

        $recent = SelectedOptionLog::orderByDesc('id')->limit(50)->get();

        return view('DoorSchedule.QuotationOptionsCheck', [
            'quotation'  => $quotation,
            'versionId'  => $versionId,
            'rows'       => $result['rows'],
            'summary'    => $result['summary'],
            'skipped'    => $result['skipped'],
            'recent'     => $recent,
            'folderName' => $this->quotationFolderName($quotation),
        ]);
    }

    /** Download a single door type as its own workbook. */
    public function download($quotationId, $versionId, Request $request)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        $door = $this->resolveDoor($quotationId, $versionId, $request);
        if (empty($door)) {
            return redirect()->back()->with('error', 'Door not found in this version.');
        }

        $version    = QuotationVersion::find($versionId);
        $folderPath = $this->versionFolderPath($quotation, $version);
        $fileName   = $this->doorFileName((string) $door->DoorType);

        $contents = Excel::raw(
            new DoorInfoExport($quotationId, $versionId, $door->itemId),
            \Maatwebsite\Excel\Excel::XLSX
        );

        $this->writeToFolder($folderPath, $fileName, $contents);

        return response($contents, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /** Download the whole folder - every door type workbook - as one zip. */
    public function downloadAll($quotationId, $versionId)
    {
        $quotation = Quotation::find($quotationId);
        if (empty($quotation)) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        if (!class_exists(ZipArchive::class)) {
            return redirect()->back()->with('error', 'Zip support is not enabled on this server. Please download the door files individually.');
        }

        $doorTypes = $this->doorTypes($quotationId, $versionId);
        if ($doorTypes === []) {
            return redirect()->back()->with('error', 'This quotation has no doors to export yet.');
        }

        $version    = QuotationVersion::find($versionId);
        $folderPath = $this->versionFolderPath($quotation, $version);
        $this->makeFolder($folderPath);

        $zipName = $this->quotationFolderName($quotation) . ' ' . $this->versionFolderName($version) . '.zip';
        $zipPath = $folderPath . DIRECTORY_SEPARATOR . $zipName;

        if (is_file($zipPath)) {
            @unlink($zipPath);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Could not create the zip file.');
        }

        foreach ($doorTypes as $doorType) {
            $contents = Excel::raw(
                new DoorInfoExport($quotationId, $versionId, $doorType['itemId']),
                \Maatwebsite\Excel\Excel::XLSX
            );

            $this->writeToFolder($folderPath, $doorType['file'], $contents);
            $zip->addFromString($doorType['file'], $contents);
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(false);
    }

    /**
     * Create the folder for a quotation. Called when a quotation is saved so the
     * folder exists from day one, before any door has been added.
     *
     * Never throws - a filesystem problem must not be able to fail a save.
     */
    public static function ensureQuotationFolder($quotation): void
    {
        try {
            if (empty($quotation) || empty($quotation->id)) {
                return;
            }

            $path = public_path(self::ROOT . DIRECTORY_SEPARATOR . self::quotationFolderNameFor($quotation));
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        } catch (\Throwable) {
            // Folder creation is a convenience - swallow anything it throws.
        }
    }

    /**
     * Door types in this quotation version, with door count and file name.
     *
     * DoorInfoExport keys its sheets by door type, so when the same door type
     * appears on more than one item row the last one wins. This mirrors that, so
     * the count shown here matches the count inside the generated file.
     */
    private function doorTypes($quotationId, $versionId): array
    {
        $doors = Item::where(['QuotationId' => $quotationId, 'VersionId' => $versionId])->get();

        $rows = [];
        foreach ($doors as $door) {
            $name = (string) $door->DoorType;
            if ($name === '') {
                continue;
            }

            $rows[$name] = [
                'name'   => $name,
                'itemId' => $door->itemId,
                'qty'    => ItemMaster::where('itemID', $door->itemId)->count(),
            ];
        }

        $usedFileNames = [];
        foreach ($rows as $name => $row) {
            $file = $this->doorFileName($name);

            // Two door types can sanitise down to the same file name - keep them apart.
            $suffix = 1;
            while (in_array(strtolower($file), $usedFileNames, true)) {
                $suffix++;
                $file = $this->doorFileName($name . ' (' . $suffix . ')');
            }

            $usedFileNames[] = strtolower($file);
            $rows[$name]['file'] = $file;
        }

        return array_values($rows);
    }

    /**
     * Resolve a door from the request.
     *
     * itemId is the real handle - a door type can be renamed at any time, which
     * would otherwise break every link pointing at it. doorType is still
     * accepted so links from before this change keep working.
     */
    private function resolveDoor($quotationId, $versionId, Request $request)
    {
        $itemId = trim((string) $request->query('itemId', ''));

        if ($itemId !== '') {
            return Item::where([
                'QuotationId' => $quotationId,
                'VersionId'   => $versionId,
                'itemId'      => $itemId,
            ])->first();
        }

        $doorType = (string) $request->query('doorType', '');
        if ($doorType === '') {
            return null;
        }

        return Item::where([
            'QuotationId' => $quotationId,
            'VersionId'   => $versionId,
            'DoorType'    => $doorType,
        ])->get()->last();
    }

    private function doorFileName($doorType): string
    {
        return self::safeName($doorType) . '.xlsx';
    }

    private function quotationFolderName($quotation): string
    {
        return self::quotationFolderNameFor($quotation);
    }

    private static function quotationFolderNameFor($quotation): string
    {
        $name = trim((string) ($quotation->QuotationName ?? ''));
        if ($name === '') {
            $name = trim((string) ($quotation->QuotationGenerationId ?? ''), '#');
        }

        if ($name === '') {
            $name = 'Quotation';
        }

        // Quotation names are not unique - the id keeps two folders apart.
        return self::safeName($name) . '_' . $quotation->id;
    }

    private function versionFolderName($version): string
    {
        return 'v' . ($version->version ?? 0);
    }

    private function versionFolderPath($quotation, $version): string
    {
        return public_path(
            self::ROOT
            . DIRECTORY_SEPARATOR . $this->quotationFolderName($quotation)
            . DIRECTORY_SEPARATOR . $this->versionFolderName($version)
        );
    }

    private function makeFolder($path): void
    {
        try {
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        } catch (\Throwable) {
            // Ignore - the download still works even if the mirror folder fails.
        }
    }

    /** Mirror the generated workbook into the folder. Best effort only. */
    private function writeToFolder($folderPath, $fileName, $contents): void
    {
        try {
            $this->makeFolder($folderPath);
            if (is_dir($folderPath)) {
                @file_put_contents($folderPath . DIRECTORY_SEPARATOR . $fileName, $contents);
            }
        } catch (\Throwable) {
            // Ignore - the browser download is the primary delivery.
        }
    }

    /**
     * Strip anything Windows, Excel or a URL would choke on.
     * Excel also caps a name at 31 characters.
     */
    public static function safeName($name): string
    {
        $clean = preg_replace('/[\\\\\\/\\?\\*\\:\\[\\]"<>\\|]+/', '-', (string) $name);
        $clean = preg_replace('/\\s+/', ' ', (string) $clean);
        $clean = trim((string) $clean, " .-");

        if ($clean === '') {
            $clean = 'Untitled';
        }

        return mb_substr($clean, 0, 31);
    }
}
