<?php

namespace App\Models\Builders;

use App\Exports\DoorInfoExport;
use App\Models\DoorChangeLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Records door changes without any call site having to ask for it.
 *
 * Doors are updated with Item::where(...)->update([...]) in roughly thirty
 * places - controllers, helpers and queued jobs. That is a query builder mass
 * update, so Eloquent model events never fire and an observer would miss almost
 * every edit. Overriding update() here catches all of them at once, and leaves
 * every one of those call sites untouched.
 */
class ItemLoggingBuilder extends Builder
{
    /** Guards against a log write triggering another log write. */
    private static bool $logging = false;

    public function update(array $values)
    {
        $before = self::$logging ? null : $this->snapshotBefore($values);

        $result = parent::update($values);

        if ($before !== null) {
            $this->recordChanges($before, $values);
        }

        return $result;
    }

    /**
     * Read only the columns being written plus the few needed to identify the
     * door, so logging never turns into a select of 255 columns.
     */
    private function snapshotBefore(array $values): ?array
    {
        try {
            $identity = ['itemId', 'QuotationId', 'VersionId', 'DoorType'];
            $fields   = array_values(array_diff(array_keys($values), DoorChangeLog::IGNORED_FIELDS));

            if ($fields === []) {
                return null;
            }

            $columns = array_values(array_unique(array_merge($identity, $fields)));

            return (clone $this)->get($columns)->all();
        } catch (\Throwable) {
            return null;
        }
    }

    private function recordChanges(array $before, array $values): void
    {
        try {
            self::$logging = true;

            $userId = Auth::check() ? Auth::id() : null;
            $now    = date('Y-m-d H:i:s');
            $rows   = [];

            foreach ($before as $row) {
                foreach ($values as $field => $newValue) {
                    if (in_array($field, DoorChangeLog::IGNORED_FIELDS, true)) {
                        continue;
                    }

                    // Expressions (DB::raw) cannot be compared meaningfully.
                    if (is_object($newValue)) {
                        continue;
                    }

                    $oldValue = $row->{$field} ?? null;

                    if (self::same($oldValue, $newValue)) {
                        continue;
                    }

                    $rows[] = [
                        'item_id'      => $row->itemId ?? null,
                        'quotation_id' => $row->QuotationId ?? null,
                        'version_id'   => $row->VersionId ?? null,
                        'door_type'    => $row->DoorType ?? null,
                        'action'       => 'updated',
                        'field'        => $field,
                        'label'        => DoorInfoExport::labelFor($field),
                        'old_value'    => self::stringify($oldValue),
                        'new_value'    => self::stringify($newValue),
                        'changed_by'   => $userId,
                        'created_at'   => $now,
                    ];
                }
            }

            if ($rows !== []) {
                foreach (array_chunk($rows, 200) as $chunk) {
                    DoorChangeLog::insert($chunk);
                }
            }
        } catch (\Throwable) {
            // History is a record, not a gate - it must never break a save.
        } finally {
            self::$logging = false;
        }
    }

    private static function same($old, $new): bool
    {
        if ($old === null && ($new === null || $new === '')) {
            return true;
        }

        if (is_numeric($old) && is_numeric($new)) {
            return (float) $old === (float) $new;
        }

        return trim((string) $old) === trim((string) $new);
    }

    private static function stringify($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return mb_substr((string) $value, 0, 2000);
    }
}
