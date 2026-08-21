<?php

namespace App\Services;

use App\Models\Item;
use App\Models\SelectedOptionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Finds doors whose saved option values are no longer available.
 *
 * The items table stores option choices as key strings (items.GlassType holds
 * "Pyroguard_EI30", not a foreign key). Nothing stops an option being removed
 * from Selected Options afterwards, and when that happens the door quietly
 * keeps pointing at a value nobody can pick any more. This walks every door and
 * reports those.
 *
 * Read-only against the door and option tables.
 */
class DoorOptionsChecker
{
    /**
     * items column => where its allowed values come from.
     *
     * 'table'    master list of the option
     * 'key'      column in that table holding the value stored on the door
     * 'selected' the per-user "these are the ones we use" table
     * 'fk'       column in the selected table pointing at the master row
     * 'owner'    column in the selected table holding the user id
     */
    private const CHECKS = [
        'GlassType' => [
            'label' => 'Glass Type', 'table' => 'glass_type', 'key' => ['Key', 'GlassType'],
            'selected' => 'selected_glass_type', 'fk' => 'glass_id', 'owner' => 'editBy',
        ],
        'OPGlassType' => [
            'label' => 'Overpanel Glass Type', 'table' => 'glass_type', 'key' => ['Key', 'GlassType'],
            'selected' => 'selected_glass_type', 'fk' => 'glass_id', 'owner' => 'editBy',
        ],
        'SideLight1GlassType' => [
            'label' => 'Side Light 1 Glass Type', 'table' => 'glass_type', 'key' => ['Key', 'GlassType'],
            'selected' => 'selected_glass_type', 'fk' => 'glass_id', 'owner' => 'editBy',
        ],
        'SideLight2GlassType' => [
            'label' => 'Side Light 2 Glass Type', 'table' => 'glass_type', 'key' => ['Key', 'GlassType'],
            'selected' => 'selected_glass_type', 'fk' => 'glass_id', 'owner' => 'editBy',
        ],
        'GlazingSystems' => [
            'label' => 'Glazing System', 'table' => 'glazing_system', 'key' => ['Key', 'GlazingSystem'],
            'selected' => 'selected_glazing_system', 'fk' => 'glazingId', 'owner' => 'userId',
        ],
        'OPGlazingSystems' => [
            'label' => 'Overpanel Glazing System', 'table' => 'glazing_system', 'key' => ['Key', 'GlazingSystem'],
            'selected' => 'selected_glazing_system', 'fk' => 'glazingId', 'owner' => 'userId',
        ],
        'ArchitraveType' => [
            'label' => 'Architrave Type', 'table' => 'architrave_type', 'key' => ['Key', 'ArchitraveType'],
            'selected' => 'selected_architrave_type', 'fk' => 'architraveTypeId', 'owner' => 'userId',
        ],
        'DoorLeafFacing' => [
            'label' => 'Door Leaf Facing', 'table' => 'door_leaf_facing', 'key' => ['Key', 'doorLeafFacing', 'doorLeafFacingValue'],
            'selected' => 'selected_door_leaf_facing', 'fk' => 'doorLeafFacingId', 'owner' => 'userId',
        ],
        'LeafConstruction' => [
            'label' => 'Leaf Construction', 'table' => 'leaf_type', 'key' => ['Key', 'LeafType'],
            'selected' => 'selected_leaf_type', 'fk' => 'leaf_id', 'owner' => 'editBy',
        ],
        'IntumescentSealColor' => [
            'label' => 'Intumescent Seal Colour', 'table' => 'intumescent_seal_color', 'key' => ['Key', 'IntumescentSealColor'],
            'selected' => 'selected_intumescent_seal_color', 'fk' => 'intumescentSealColorId', 'owner' => 'userId',
        ],
    ];

    /** Values that are a plain yes/no answer, not a chosen option. */
    private const NOT_OPTIONS = ['', 'no', 'yes', 'none', 'null', '0'];

    public function __construct(private $ownerId = null)
    {
    }

    /**
     * @return array{rows: array, summary: array, skipped: array}
     */
    public function check($quotationId, $versionId): array
    {
        $userIds = $this->ownerUserIds();

        $available = [];
        $known     = [];
        $skipped   = [];

        foreach (self::CHECKS as $field => $c) {
            $cacheKey = $c['table'] . '|' . $c['selected'];

            if (isset($available[$cacheKey])) {
                continue;
            }

            if (!$this->usable($c)) {
                $skipped[$c['label']] = $c['table'];
                continue;
            }

            $available[$cacheKey] = $this->selectedKeys($c, $userIds);
            $known[$cacheKey]     = $this->allKeys($c);
        }

        $rows    = [];
        $summary = ['ok' => 0, 'not_selected' => 0, 'missing' => 0];

        $doors = Item::where(['QuotationId' => $quotationId, 'VersionId' => $versionId])->get();

        foreach ($doors as $door) {
            foreach (self::CHECKS as $field => $c) {
                $cacheKey = $c['table'] . '|' . $c['selected'];
                if (!isset($available[$cacheKey])) {
                    continue;
                }

                $value = trim((string) ($door->{$field} ?? ''));
                if (in_array(strtolower($value), self::NOT_OPTIONS, true)) {
                    continue;
                }

                if (isset($available[$cacheKey][$value])) {
                    $summary['ok']++;
                    continue;
                }

                $status = isset($known[$cacheKey][$value]) ? 'not_selected' : 'missing';
                $summary[$status]++;

                $rows[] = [
                    'door_type' => $door->DoorType,
                    'item_id'   => $door->itemId,
                    'field'     => $field,
                    'label'     => $c['label'],
                    'value'     => $value,
                    'status'    => $status,
                ];
            }
        }

        return ['rows' => $rows, 'summary' => $summary, 'skipped' => $skipped];
    }

    /**
     * Compare the current Selected Options against the last time we looked and
     * record what was added or removed.
     *
     * Note the timestamp is when the change was DETECTED, not when someone made
     * it - options are removed through many code paths and none of them log.
     */
    public function reconcileLog(): array
    {
        $userIds = $this->ownerUserIds();
        $ownerId = (string) ($userIds[count($userIds) - 1] ?? '0');
        $changes = [];

        if (!Schema::hasTable('selected_option_snapshots') || !Schema::hasTable('selected_option_logs')) {
            return $changes;
        }

        $seen = [];
        foreach (self::CHECKS as $c) {
            if (isset($seen[$c['selected']]) || !$this->usable($c)) {
                continue;
            }

            $seen[$c['selected']] = true;
            $type    = $c['label'];
            $current = array_keys($this->selectedKeys($c, $userIds));

            $previousRows = DB::table('selected_option_snapshots')
                ->where('owner_id', $ownerId)->where('option_type', $c['table'])->get();

            $previous = $previousRows->pluck('option_key')->all();
            $isFirstRun = $previousRows->isEmpty();

            $added   = array_diff($current, $previous);
            $removed = array_diff($previous, $current);

            // On the very first run there is nothing to compare against - just
            // record the baseline, do not report every option as newly added.
            if (!$isFirstRun) {
                foreach (['added' => $added, 'removed' => $removed] as $action => $keys) {
                    foreach ($keys as $key) {
                        SelectedOptionLog::create([
                            'owner_id'     => $ownerId,
                            'option_type'  => $type,
                            'option_key'   => $key,
                            'option_label' => $key,
                            'action'       => $action,
                            'detected_by'  => Auth::check() ? Auth::id() : null,
                            'created_at'   => date('Y-m-d H:i:s'),
                        ]);

                        $changes[] = ['type' => $type, 'key' => $key, 'action' => $action];
                    }
                }
            }

            if ($added !== [] || $removed !== [] || $isFirstRun) {
                DB::table('selected_option_snapshots')
                    ->where('owner_id', $ownerId)->where('option_type', $c['table'])->delete();

                $insert = [];
                foreach ($current as $key) {
                    $insert[] = [
                        'owner_id'    => $ownerId,
                        'option_type' => $c['table'],
                        'option_key'  => $key,
                        'created_at'  => date('Y-m-d H:i:s'),
                    ];
                }

                foreach (array_chunk($insert, 500) as $chunk) {
                    DB::table('selected_option_snapshots')->insert($chunk);
                }
            }
        }

        return $changes;
    }

    /**
     * Keys currently ticked in Selected Options.
     *
     * A door does not always store the same column: items.DoorLeafFacing holds
     * the Key ("Oak") on some rows and the category name ("Laminate") on others.
     * Matching on one column alone reports perfectly good doors as broken, so
     * every candidate column counts as a match.
     */
    private function selectedKeys(array $c, array $userIds): array
    {
        $found = [];

        foreach ($this->keyColumns($c) as $column) {
            $keys = DB::table($c['table'])
                ->join($c['selected'], $c['selected'] . '.' . $c['fk'], '=', $c['table'] . '.id')
                ->whereIn($c['selected'] . '.' . $c['owner'], $userIds)
                ->distinct()
                ->pluck($c['table'] . '.' . $column)
                ->all();

            foreach ($keys as $k) {
                if ($k !== null && $k !== '') {
                    $found[$k] = true;
                }
            }
        }

        return $found;
    }

    /** Every value in the master list, selected or not. */
    private function allKeys(array $c): array
    {
        $found = [];

        foreach ($this->keyColumns($c) as $column) {
            foreach (DB::table($c['table'])->distinct()->pluck($column)->all() as $k) {
                if ($k !== null && $k !== '') {
                    $found[$k] = true;
                }
            }
        }

        return $found;
    }

    /** Candidate columns that a door value might have been stored from. */
    private function keyColumns(array $c): array
    {
        $columns = is_array($c['key']) ? $c['key'] : [$c['key']];

        return array_values(array_filter(
            $columns,
            fn ($col): bool => Schema::hasColumn($c['table'], $col)
        ));
    }

    /** Only run a check when its tables and columns really exist. */
    private function usable(array $c): bool
    {
        try {
            if (!Schema::hasTable($c['table']) || !Schema::hasTable($c['selected'])) {
                return false;
            }

            return $this->keyColumns($c) !== []
                && Schema::hasColumn($c['selected'], $c['fk'])
                && Schema::hasColumn($c['selected'], $c['owner']);
        } catch (\Throwable) {
            return false;
        }
    }

    private function ownerUserIds(): array
    {
        try {
            $ids = CompanyUsers(false, $this->ownerId);

            return is_array($ids) && $ids !== [] ? $ids : ['1'];
        } catch (\Throwable) {
            return ['1'];
        }
    }
}
