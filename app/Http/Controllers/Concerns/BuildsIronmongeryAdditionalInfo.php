<?php

namespace App\Http\Controllers\Concerns;

use App\Models\SelectedIronmongery;
use App\Models\IronmongeryInfoModel;
use Illuminate\Support\Facades\Auth;

/**
 * Shared, performant builder for the `additional_info` attribute attached to each
 * ironmongery set before it is sent to the browser (inside IronmongeryJson).
 *
 * Why this exists:
 *  - The previous per-controller code ran DB queries INSIDE nested loops (a classic
 *    N+1) — thousands of queries per page load, which hammered MariaDB during the
 *    load spike. Here every SelectedIronmongery and IronmongeryInfoModel row is loaded
 *    in bulk up front and then looked up from in-memory maps (a handful of queries).
 *  - Each entry used to be a FULL IronmongeryInfoModel row (~34 columns) repeated by
 *    quantity, which bloated the response to tens of MB. Here each entry is slimmed to
 *    only the fields the front-end actually reads.
 *
 * The output is otherwise identical to the original logic: same models, same order,
 * and (for the canonical builder) the same quantity duplication — so the CAD drawing's
 * per-category [0]/[1] logic is unaffected.
 */
trait BuildsIronmongeryAdditionalInfo
{
    /**
     * The ONLY fields any door brand's CAD JavaScript reads from an additional_info
     * element (verified across every brand's *-cad-door-configuration.js).
     *
     * IMPORTANT (future maintenance): if the front-end is ever made to read another
     * field from an additional_info element, add that field name here — otherwise it
     * will arrive `undefined` in the browser. Keep the list as small as the JS allows.
     */
    private static $ironmongeryAdditionalInfoFields = [
        'Category',
        'staticHeight',
        'staticWidth',
        'distanceFromBottomOfDoor',
        'distanceFromLeadingEdgeOfDoor',
        'centered',
    ];

    /**
     * Canonical builder: each category field may hold a comma-separated list of
     * SelectedIronmongery ids, with a matching comma-separated quantity field, and
     * each resolved item is repeated `quantity` times. Mirrors the original
     * nested-loop logic exactly, minus the per-row queries and minus the unused
     * columns.
     */
    protected function attachIronmongeryAdditionalInfo($setIronmongery, array $ironmongeryInfoSet)
    {
        $authUserId = Auth::user()->id;

        $qtyFieldOverrides = [
            'DoorSinage' => 'doorSignageQty',
            'FaceFixedDoorCloser' => 'faceFixedDoorClosersQty',
            'DoorStops' => 'DoorStopsQty',
            'AirTransferGrill' => 'airtransfergrillsQty',
        ];

        // 1) Collect every referenced SelectedIronmongery id (comma-split).
        $selectedIds = [];
        foreach ($setIronmongery as $ironmongery) {
            foreach ($ironmongeryInfoSet as $valIronmongery) {
                if (!empty($ironmongery->$valIronmongery)) {
                    foreach (explode(',', $ironmongery->$valIronmongery) as $sid) {
                        $sid = trim($sid);
                        if ($sid !== '') {
                            $selectedIds[$sid] = true;
                        }
                    }
                }
            }
        }

        [$selectedMap, $infoByIronmongeryId, $infoById] =
            $this->preloadIronmongeryInfo(array_keys($selectedIds), $authUserId);

        // 2) Build additional_info from in-memory maps, preserving order + quantity.
        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = [];

            foreach ($ironmongeryInfoSet as $valIronmongery) {
                $qtyField = $qtyFieldOverrides[$valIronmongery] ?? lcfirst($valIronmongery) . 'Qty';

                if (!empty($ironmongery->$valIronmongery)) {
                    $ids = explode(',', $ironmongery->$valIronmongery);
                    $qtys = !empty($ironmongery->$qtyField) ? explode(',', $ironmongery->$qtyField) : [];

                    foreach ($ids as $index => $itemId) {
                        $itemId = trim($itemId);
                        $qty = isset($qtys[$index]) ? (int) trim($qtys[$index]) : 1;

                        $SelectedIronmongery = $selectedMap->get($itemId);

                        if ($SelectedIronmongery) {
                            $ironmongeryId = $SelectedIronmongery->ironmongery_id;
                            $info = $infoByIronmongeryId[$ironmongeryId]
                                ?? ($infoById[$ironmongeryId] ?? null);

                            if ($info) {
                                $slim = $this->slimIronmongeryInfo($info);
                                for ($i = 0; $i < $qty; $i++) {
                                    $additionalInfo[] = $slim;
                                }
                            }
                        }
                    }
                }
            }

            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }
    }

    /**
     * Legacy/simple builder: each category field holds a single SelectedIronmongery id
     * and the resolved item is added once (no comma-split, no quantity duplication).
     * Mirrors the older controller logic exactly, minus the per-row queries and unused
     * columns.
     */
    protected function attachIronmongeryAdditionalInfoSingle($setIronmongery, array $ironmongeryInfoSet)
    {
        $authUserId = Auth::user()->id;

        // 1) Collect every referenced SelectedIronmongery id (whole field value).
        $selectedIds = [];
        foreach ($setIronmongery as $ironmongery) {
            foreach ($ironmongeryInfoSet as $valIronmongery) {
                if (!empty($ironmongery->$valIronmongery)) {
                    $selectedIds[$ironmongery->$valIronmongery] = true;
                }
            }
        }

        [$selectedMap, $infoByIronmongeryId, $infoById] =
            $this->preloadIronmongeryInfo(array_keys($selectedIds), $authUserId);

        // 2) Build additional_info from in-memory maps.
        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = [];

            foreach ($ironmongeryInfoSet as $valIronmongery) {
                if (!empty($ironmongery->$valIronmongery)) {
                    // (int) cast mirrors MySQL's implicit string→int comparison used by the
                    // original `where('id', $value)` query (e.g. "5,8" matched id 5).
                    $SelectedIronmongery = $selectedMap->get((int) $ironmongery->$valIronmongery);

                    if ($SelectedIronmongery) {
                        $ironmongeryId = $SelectedIronmongery->ironmongery_id;
                        $info = $infoByIronmongeryId[$ironmongeryId]
                            ?? ($infoById[$ironmongeryId] ?? null);

                        if ($info) {
                            $additionalInfo[] = $this->slimIronmongeryInfo($info);
                        }
                    }
                }
            }

            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }
    }

    /**
     * Legacy/simple builder variant where the IronmongeryInfoModel is resolved ONLY by
     * its primary id (no IronmongeryId+user primary lookup). Single id per field, added
     * once. Mirrors the older controller logic that used
     * `IronmongeryInfoModel::where('id', $selected->ironmongery_id)->first()`.
     */
    protected function attachIronmongeryAdditionalInfoByInfoId($setIronmongery, array $ironmongeryInfoSet)
    {
        $authUserId = Auth::user()->id;

        $selectedIds = [];
        foreach ($setIronmongery as $ironmongery) {
            foreach ($ironmongeryInfoSet as $valIronmongery) {
                if (!empty($ironmongery->$valIronmongery)) {
                    $selectedIds[$ironmongery->$valIronmongery] = true;
                }
            }
        }

        [$selectedMap, , $infoById] =
            $this->preloadIronmongeryInfo(array_keys($selectedIds), $authUserId);

        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = [];

            foreach ($ironmongeryInfoSet as $valIronmongery) {
                if (!empty($ironmongery->$valIronmongery)) {
                    // (int) cast mirrors MySQL's implicit string→int comparison used by the
                    // original `where('id', $value)` query (e.g. "5,8" matched id 5).
                    $SelectedIronmongery = $selectedMap->get((int) $ironmongery->$valIronmongery);

                    if ($SelectedIronmongery) {
                        $info = $infoById[$SelectedIronmongery->ironmongery_id] ?? null;
                        if ($info) {
                            $additionalInfo[] = $this->slimIronmongeryInfo($info);
                        }
                    }
                }
            }

            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }
    }

    /**
     * Bulk-load the SelectedIronmongery rows (for the current user) and their
     * IronmongeryInfoModel rows. Returns [selectedMap, infoByIronmongeryId, infoById].
     * "First match wins" matches the previous ->first() behaviour.
     */
    private function preloadIronmongeryInfo(array $selectedIds, $authUserId): array
    {
        if (empty($selectedIds)) {
            return [collect(), [], []];
        }

        $selectedMap = SelectedIronmongery::whereIn('id', $selectedIds)
            ->where('UserId', $authUserId)
            ->get()
            ->keyBy('id');

        $ironmongeryIds = $selectedMap->pluck('ironmongery_id')
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->unique()
            ->values()
            ->all();

        $infoByIronmongeryId = [];
        $infoById = [];
        if (!empty($ironmongeryIds)) {
            foreach (IronmongeryInfoModel::whereIn('IronmongeryId', $ironmongeryIds)
                        ->where('UserId', $authUserId)->get() as $info) {
                if (!isset($infoByIronmongeryId[$info->IronmongeryId])) {
                    $infoByIronmongeryId[$info->IronmongeryId] = $info;
                }
            }
            foreach (IronmongeryInfoModel::whereIn('id', $ironmongeryIds)->get() as $info) {
                if (!isset($infoById[$info->id])) {
                    $infoById[$info->id] = $info;
                }
            }
        }

        return [$selectedMap, $infoByIronmongeryId, $infoById];
    }

    /**
     * Reduce a full IronmongeryInfoModel row to only the fields the front-end reads.
     */
    private function slimIronmongeryInfo($info): array
    {
        $slim = [];
        foreach (self::$ironmongeryAdditionalInfoFields as $field) {
            $slim[$field] = $info->$field;
        }
        return $slim;
    }
}
