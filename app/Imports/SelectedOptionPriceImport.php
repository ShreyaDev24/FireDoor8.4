<?php

namespace App\Imports;

use App\Models\IntumescentSealLeafType;
use App\Support\SelectedOptionExportColumns;
use App\Support\SelectedOptionModuleRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Throwable;

class SelectedOptionPriceImport
{
    private const MAX_PRICE = 99999999.99;

    /**
     * Read an exported workbook and update only the price column for the
     * logged-in user's selected options. Nothing is written when any row fails.
     *
     * @return array{status: string, message: string, processed: int, updated: int, skipped: int, failed: int, errors: array<int, array{row: int, reason: string}>}
     */
    public function import(string $path, string $moduleKey, int $userId): array
    {
        $module = SelectedOptionModuleRegistry::get($moduleKey);

        if ($module === null) {
            return $this->failure('Unknown selected option module.', 0, [[
                'row' => 0,
                'reason' => 'Unknown selected option module.',
            ]]);
        }

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (Throwable $exception) {
            return $this->failure('The file is not a valid Excel workbook (.xlsx or .xls).', 0, [[
                'row' => 0,
                'reason' => 'The file is not a valid Excel workbook (.xlsx or .xls).',
            ]]);
        }

        $sheet = $spreadsheet->getSheet(0);
        $parsed = $this->readSheet($sheet);

        if ($parsed['error'] !== null) {
            return $this->failure($parsed['error'], 0, [[
                'row' => 1,
                'reason' => $parsed['error'],
            ]]);
        }

        $headerError = $this->validateHeaders($module, $parsed);
        if ($headerError !== null) {
            return $this->failure($headerError, 0, [[
                'row' => 1,
                'reason' => $headerError,
            ]]);
        }

        $rows = $this->readRows($sheet, $module, $moduleKey, $parsed);
        if (isset($rows['failure'])) {
            return $rows['failure'];
        }

        if ($rows['data'] === [] && $rows['errors'] === []) {
            return $this->failure('The Excel file has no data rows.', 0, [[
                'row' => 2,
                'reason' => 'The Excel file has no data rows.',
            ]]);
        }

        $errors = $rows['errors'];
        $processed = count($rows['data']);

        foreach ($rows['data'] as $row) {
            $master = ($module['master'])($row['record_id'], $userId);
            if (!$master) {
                $errors[] = [
                    'row' => $row['row'],
                    'reason' => 'This record is not available for your account.',
                ];
            }
        }

        if ($errors !== []) {
            usort($errors, fn ($left, $right) => $left['row'] <=> $right['row']);
            return $this->failure(
                'Import was not saved. ' . count($errors) . ' ' . (count($errors) === 1 ? 'row needs' : 'rows need') . ' a correction.',
                $processed,
                $errors
            );
        }

        try {
            $counts = DB::transaction(function () use ($module, $userId, $rows) {
                return $this->apply($module, $userId, $rows['data']);
            });
        } catch (Throwable $exception) {
            report($exception);

            return $this->failure('The import could not be saved and no prices were changed.', $processed, [[
                'row' => 0,
                'reason' => 'The import could not be saved and no prices were changed.',
            ]]);
        }

        $message = 'Updated ' . $counts['updated'] . ' ' . ($counts['updated'] === 1 ? 'price row' : 'price rows') . '.';
        if ($counts['skipped'] > 0) {
            $message .= ' Skipped ' . $counts['skipped'] . ' unchanged ' . ($counts['skipped'] === 1 ? 'row' : 'rows') . '.';
        }

        return [
            'status' => 'ok',
            'message' => $message,
            'processed' => $processed,
            'updated' => $counts['updated'],
            'skipped' => $counts['skipped'],
            'failed' => 0,
            'errors' => [],
        ];
    }

    /**
     * @return array{headers: array<int, string>, columns: array<string, int>, error: ?string}
     */
    private function readSheet($sheet): array
    {
        $highestIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $headers = [];

        for ($index = 1; $index <= $highestIndex; $index++) {
            $headers[$index] = $this->cellText($sheet->getCell(Coordinate::stringFromColumnIndex($index) . '1')->getValue());
        }

        while ($headers !== [] && end($headers) === '') {
            $last = array_key_last($headers);
            unset($headers[$last]);
        }

        if ($headers === [] || in_array('', $headers, true)) {
            return [
                'headers' => [],
                'columns' => [],
                'error' => 'The Excel file is missing its header row.',
            ];
        }

        $columns = [];
        foreach ($headers as $index => $header) {
            $columns[$header] = $index;
        }

        return [
            'headers' => $headers,
            'columns' => $columns,
            'error' => null,
        ];
    }

    /**
     * @param  array<string, mixed>  $module
     * @param  array{headers: array<int, string>, columns: array<string, int>}  $parsed
     */
    private function validateHeaders(array $module, array $parsed): ?string
    {
        $headers = array_values($parsed['headers']);
        $hidden = [SelectedOptionExportColumns::RECORD_ID, SelectedOptionExportColumns::MODULE];
        $visible = [];
        $seenHidden = false;

        foreach ($headers as $header) {
            if (str_starts_with($header, '_')) {
                $seenHidden = true;
                continue;
            }

            if ($seenHidden) {
                return 'The Excel columns are not in the exported order. Export this module again and edit only the price cells.';
            }

            $visible[] = $header;
        }

        $expectedHidden = $hidden;
        if (($module['price_mode'] ?? '') === 'leaf_json') {
            $expectedHidden[] = SelectedOptionExportColumns::LEAF_MAP;
        }

        $actualHidden = array_values(array_filter($headers, fn ($header) => str_starts_with($header, '_')));

        if ($actualHidden !== $expectedHidden) {
            return 'This file has no import identifiers for ' . $module['label'] . '. Export the selected options again, then import that file.';
        }

        if (($module['price_mode'] ?? '') === 'leaf_json') {
            $fixed = $module['fixed_headers'];
            $prefix = array_slice($visible, 0, count($fixed));

            if ($prefix !== $fixed || count($visible) <= count($fixed)) {
                return 'This file does not match the ' . $module['label'] . ' export. Expected the first columns to be: ' . implode(', ', $fixed) . '.';
            }

            return null;
        }

        if ($visible !== $module['headers']) {
            return 'This file does not match the ' . $module['label'] . ' export. Expected columns: ' . implode(', ', $module['headers']) . '.';
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $module
     * @param  array{headers: array<int, string>, columns: array<string, int>}  $parsed
     * @return array{data: array<int, array<string, mixed>>, errors: array<int, array{row: int, reason: string}>}|array{failure: array<string, mixed>}
     */
    private function readRows($sheet, array $module, string $moduleKey, array $parsed): array
    {
        $errors = [];
        $data = [];
        $seenIds = [];
        $recordColumn = $parsed['columns'][SelectedOptionExportColumns::RECORD_ID];
        $moduleColumn = $parsed['columns'][SelectedOptionExportColumns::MODULE];
        $priceColumns = $this->priceColumns($sheet, $module, $parsed, $errors);

        if ($priceColumns === null) {
            return ['failure' => $this->failure($errors[0]['reason'], 0, $errors)];
        }

        $highestRow = $sheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $recordValue = $sheet->getCell(Coordinate::stringFromColumnIndex($recordColumn) . $row)->getValue();
            $moduleValue = $this->cellText($sheet->getCell(Coordinate::stringFromColumnIndex($moduleColumn) . $row)->getValue());
            $prices = [];
            $priceTexts = [];

            foreach ($priceColumns as $priceColumn) {
                $raw = $sheet->getCell(Coordinate::stringFromColumnIndex($priceColumn['index']) . $row)->getValue();
                $prices[$priceColumn['key']] = $raw;
                $priceTexts[] = $this->cellText($raw);
            }

            if ($this->cellText($recordValue) === '' && $moduleValue === '' && implode('', $priceTexts) === '') {
                continue;
            }

            $recordId = $this->parseRecordId($recordValue);
            if ($recordId === null) {
                $errors[] = [
                    'row' => $row,
                    'reason' => 'The hidden record identifier is missing or invalid. Export the file again and do not edit the hidden columns.',
                ];
                continue;
            }

            if ($moduleValue === '') {
                $errors[] = [
                    'row' => $row,
                    'reason' => 'The hidden module identifier is missing. Export the file again.',
                ];
                continue;
            }

            if ($moduleValue !== $moduleKey) {
                $other = SelectedOptionModuleRegistry::get($moduleValue);
                $otherLabel = $other['label'] ?? $moduleValue;
                $errors[] = [
                    'row' => $row,
                    'reason' => 'This row belongs to ' . $otherLabel . ', not ' . $module['label'] . '.',
                ];
                continue;
            }

            if (isset($seenIds[$recordId])) {
                $errors[] = [
                    'row' => $row,
                    'reason' => 'Record ' . $recordId . ' is duplicated. Row ' . $seenIds[$recordId] . ' already uses it.',
                ];
                continue;
            }

            $seenIds[$recordId] = $row;
            $parsedPrices = [];
            $rowFailed = false;

            foreach ($priceColumns as $priceColumn) {
                $parsedPrice = $this->parsePrice($prices[$priceColumn['key']], $row, $priceColumn['label'], $errors);
                if ($parsedPrice === null) {
                    $rowFailed = true;
                    continue;
                }
                $parsedPrices[$priceColumn['key']] = $parsedPrice;
            }

            if ($rowFailed) {
                continue;
            }

            $data[] = [
                'row' => $row,
                'record_id' => $recordId,
                'prices' => $parsedPrices,
            ];
        }

        return ['data' => $data, 'errors' => $errors];
    }

    /**
     * @param  array<string, mixed>  $module
     * @param  array{headers: array<int, string>, columns: array<string, int>}  $parsed
     * @param  array<int, array{row: int, reason: string}>  $errors
     * @return array<int, array{index: int, key: string, label: string}>|null
     */
    private function priceColumns($sheet, array $module, array $parsed, array &$errors): ?array
    {
        if (($module['price_mode'] ?? '') === 'leaf_json') {
            $columns = [];
            $leafMap = $this->leafMap($sheet, $parsed, $errors);
            if ($leafMap === null) {
                return null;
            }

            $fixedCount = count($module['fixed_headers']);
            $visibleIndex = 0;

            foreach ($parsed['headers'] as $index => $header) {
                if (str_starts_with($header, '_')) {
                    continue;
                }

                $visibleIndex++;
                if ($visibleIndex <= $fixedCount) {
                    continue;
                }

                $letter = Coordinate::stringFromColumnIndex($index);
                $leafId = $this->leafIdForColumn($sheet, $letter, $leafMap);

                if ($leafId === null) {
                    $errors[] = [
                        'row' => 1,
                        'reason' => 'Column ' . $letter . ' (' . $header . ') has no leaf identifier. Export the file again.',
                    ];
                    return null;
                }

                $leaf = IntumescentSealLeafType::query()->find($leafId);
                if (!$leaf || SelectedOptionModuleRegistry::leafHeader($leaf) !== $header) {
                    $errors[] = [
                        'row' => 1,
                        'reason' => 'Column ' . $letter . ' does not match a current leaf cost column.',
                    ];
                    return null;
                }

                $columns[] = [
                    'index' => $index,
                    'key' => (string) $leafId,
                    'label' => $header,
                ];
            }

            if ($columns === []) {
                $errors[] = [
                    'row' => 1,
                    'reason' => 'This Door Dimension Custom file has no leaf cost columns.',
                ];
                return null;
            }

            return $columns;
        }

        $columns = [];
        foreach ($module['prices'] as $label => $column) {
            if (!isset($parsed['columns'][$label])) {
                $errors[] = [
                    'row' => 1,
                    'reason' => 'The "' . $label . '" column is missing.',
                ];
                return null;
            }

            $columns[] = [
                'index' => $parsed['columns'][$label],
                'key' => $column,
                'label' => $label,
            ];
        }

        return $columns;
    }

    /**
     * @param  array{columns: array<string, int>}  $parsed
     * @param  array<int, array{row: int, reason: string}>  $errors
     * @return array<string, int>|null
     */
    private function leafMap($sheet, array $parsed, array &$errors): ?array
    {
        $column = $parsed['columns'][SelectedOptionExportColumns::LEAF_MAP] ?? null;
        if ($column === null) {
            $errors[] = [
                'row' => 1,
                'reason' => 'The leaf cost map is missing. Export Door Dimension Custom again.',
            ];
            return null;
        }

        $raw = '';
        $highestRow = max(2, $sheet->getHighestRow());

        for ($row = 2; $row <= $highestRow; $row++) {
            $raw = $this->cellText($sheet->getCell(Coordinate::stringFromColumnIndex($column) . $row)->getValue());
            if ($raw !== '') {
                break;
            }
        }

        if ($raw === '') {
            $raw = trim($sheet->getComment(Coordinate::stringFromColumnIndex($column) . '1')->getText()->getPlainText());
        }

        $decoded = json_decode($raw, true);

        if (!is_array($decoded) || $decoded === []) {
            $errors[] = [
                'row' => 1,
                'reason' => 'The leaf cost map could not be read. Export Door Dimension Custom again.',
            ];
            return null;
        }

        $map = [];
        foreach ($decoded as $letter => $leafId) {
            if (!is_numeric($leafId) || (int) $leafId <= 0) {
                $errors[] = [
                    'row' => 1,
                    'reason' => 'The leaf cost map contains an invalid identifier.',
                ];
                return null;
            }
            $map[(string) $letter] = (int) $leafId;
        }

        return $map;
    }

    /**
     * @param  array<string, int>  $leafMap
     */
    private function leafIdForColumn($sheet, string $letter, array $leafMap): ?int
    {
        $comment = trim($sheet->getComment($letter . '1')->getText()->getPlainText());
        $mapped = $leafMap[$letter] ?? null;

        if ($comment !== '' && ctype_digit($comment)) {
            $commentId = (int) $comment;
            if ($mapped !== null && $mapped !== $commentId) {
                return null;
            }

            return $commentId;
        }

        return $mapped;
    }

    /**
     * @param  array<string, mixed>  $module
     * @param  array<int, array{row: int, record_id: int, prices: array<string, string>}>  $data
     * @return array{updated: int, skipped: int}
     */
    private function apply(array $module, int $userId, array $data): array
    {
        $updated = 0;
        $skipped = 0;
        $modelClass = $module['model'];
        $owner = $module['owner'];
        $foreign = $module['foreign'];

        foreach ($data as $row) {
            /** @var Model|null $master */
            $master = ($module['master'])($row['record_id'], $userId);

            if (!$master) {
                throw new \RuntimeException('Record ' . $row['record_id'] . ' is not available for this account.');
            }

            $selectedRows = $modelClass::query()
                ->where($owner, $userId)
                ->where($foreign, $row['record_id'])
                ->lockForUpdate()
                ->get();

            if (($module['price_mode'] ?? '') === 'leaf_json') {
                $changed = $this->applyLeafPrices($selectedRows, $module, $master, $userId, $row['prices']);
            } else {
                $changed = $this->applyColumnPrices($selectedRows, $module, $master, $userId, $row['record_id'], $row['prices']);
            }

            if ($changed) {
                $updated++;
            } else {
                $skipped++;
            }
        }

        return ['updated' => $updated, 'skipped' => $skipped];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Model>  $selectedRows
     * @param  array<string, mixed>  $module
     * @param  array<string, string>  $prices
     */
    private function applyColumnPrices($selectedRows, array $module, Model $master, int $userId, int $recordId, array $prices): bool
    {
        if ($selectedRows->isEmpty()) {
            $attributes = [
                $module['owner'] => $userId,
                $module['foreign'] => $recordId,
            ];

            if (is_callable($module['create_with'])) {
                $attributes = array_merge($attributes, $module['create_with']($master));
            }

            $attributes = array_merge($attributes, $prices);
            $module['model']::query()->create($attributes);

            return true;
        }

        $changed = false;

        foreach ($selectedRows as $selected) {
            $updates = [];

            foreach ($prices as $column => $price) {
                if (!$this->samePrice($selected->getAttribute($column), $price)) {
                    $updates[$column] = $price;
                }
            }

            if ($updates === []) {
                continue;
            }

            $selected->fill($updates);
            $selected->save();
            $changed = true;
        }

        return $changed;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Model>  $selectedRows
     * @param  array<string, mixed>  $module
     * @param  array<string, string>  $prices
     */
    private function applyLeafPrices($selectedRows, array $module, Model $master, int $userId, array $prices): bool
    {
        $column = $module['price_column'];

        if ($selectedRows->isEmpty()) {
            $attributes = [
                $module['owner'] => $userId,
                $module['foreign'] => $master->getKey(),
                $column => $this->mergeLeafPrices([], $prices),
            ];

            if (is_callable($module['create_with'])) {
                $attributes = array_merge($module['create_with']($master), $attributes);
            }

            $module['model']::query()->create($attributes);

            return true;
        }

        $changed = false;

        foreach ($selectedRows as $selected) {
            $current = $selected->getAttribute($column);
            if (!is_array($current)) {
                $current = [];
            }

            $merged = $this->mergeLeafPrices($current, $prices);
            if ($this->sameLeafPrices($current, $merged)) {
                continue;
            }

            $selected->setAttribute($column, $merged);
            $selected->save();
            $changed = true;
        }

        return $changed;
    }

    /**
     * @param  array<int|string, mixed>  $current
     * @param  array<string, string>  $prices
     * @return array<int|string, mixed>
     */
    private function mergeLeafPrices(array $current, array $prices): array
    {
        foreach ($prices as $leafId => $price) {
            $current[(int) $leafId] = $price;
        }

        return $current;
    }

    /**
     * @param  array<int|string, mixed>  $current
     * @param  array<int|string, mixed>  $merged
     */
    private function sameLeafPrices(array $current, array $merged): bool
    {
        foreach ($merged as $leafId => $price) {
            $existing = $current[$leafId] ?? ($current[(string) $leafId] ?? null);
            if (!$this->samePrice($existing, $price)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<int, array{row: int, reason: string}>  $errors
     */
    private function parsePrice($value, int $excelRow, string $label, array &$errors): ?string
    {
        if ($value instanceof RichText) {
            $value = $value->getPlainText();
        }

        if (is_string($value)) {
            $original = trim($value);

            if ($original === '') {
                $errors[] = [
                    'row' => $excelRow,
                    'reason' => $label . ' is empty. An empty price is not saved as zero.',
                ];
                return null;
            }

            if (str_starts_with($original, '=')) {
                $errors[] = [
                    'row' => $excelRow,
                    'reason' => $label . ' contains a formula. Enter a number.',
                ];
                return null;
            }

            $normalised = str_replace(['£', '$', '€', ' '], '', $original);

            if (preg_match('/^\d{1,3}(,\d{3})+(\.\d+)?$/', $normalised)) {
                $normalised = str_replace(',', '', $normalised);
            }

            if (preg_match('/^-(\d+(\.\d+)?)$/', $normalised)) {
                $errors[] = [
                    'row' => $excelRow,
                    'reason' => $label . ' cannot be negative.',
                ];
                return null;
            }

            if (!preg_match('/^\d+(\.\d+)?$/', $normalised)) {
                $errors[] = [
                    'row' => $excelRow,
                    'reason' => $label . ' must be a number zero or greater. "' . $original . '" is not valid.',
                ];
                return null;
            }

            $value = (float) $normalised;
        } elseif (is_int($value) || is_float($value)) {
            $value = (float) $value;
            if (!is_finite($value)) {
                $errors[] = [
                    'row' => $excelRow,
                    'reason' => $label . ' must be a number zero or greater.',
                ];
                return null;
            }
        } else {
            $errors[] = [
                'row' => $excelRow,
                'reason' => $label . ' is empty. An empty price is not saved as zero.',
            ];
            return null;
        }

        if ($value < 0) {
            $errors[] = [
                'row' => $excelRow,
                'reason' => $label . ' cannot be negative.',
            ];
            return null;
        }

        if ($value > self::MAX_PRICE) {
            $errors[] = [
                'row' => $excelRow,
                'reason' => $label . ' is larger than the supported price range.',
            ];
            return null;
        }

        return number_format(round($value, 2), 2, '.', '');
    }

    private function parseRecordId($value): ?int
    {
        if ($value instanceof RichText) {
            $value = $value->getPlainText();
        }

        if (is_string($value)) {
            $value = trim($value);
            if (!preg_match('/^\d+$/', $value)) {
                return null;
            }
            $value = (int) $value;
        } elseif (is_int($value)) {
            // Already an integer identifier.
        } elseif (is_float($value)) {
            if ($value <= 0 || floor($value) !== $value) {
                return null;
            }
            $value = (int) $value;
        } else {
            return null;
        }

        return $value > 0 ? $value : null;
    }

    private function cellText($value): string
    {
        if ($value instanceof RichText) {
            $value = $value->getPlainText();
        }

        if ($value === null) {
            return '';
        }

        return trim((string) $value);
    }

    private function samePrice($current, $new): bool
    {
        if ($current === null || $current === '') {
            return false;
        }

        return abs((float) $current - (float) $new) < 0.00001;
    }

    /**
     * @param  array<int, array{row: int, reason: string}>  $errors
     * @return array{status: string, message: string, processed: int, updated: int, skipped: int, failed: int, errors: array<int, array{row: int, reason: string}>}
     */
    private function failure(string $message, int $processed, array $errors): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'processed' => $processed,
            'updated' => 0,
            'skipped' => 0,
            'failed' => count($errors),
            'errors' => $errors,
        ];
    }
}
