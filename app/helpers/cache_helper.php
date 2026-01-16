<?php
/**
 * Cache Helper Functions
 * Provides query caching to reduce database load
 */

use Illuminate\Support\Facades\Auth;
use App\Models\QuotationVersion;
use App\Models\SelectedIronmongery;
use App\Models\IronmongeryInfoModel;

/**
 * Cache company users for the current session
 * Reduces repeated User::where() queries
 *
 * @param bool $isstatus
 * @return array
 */
function getCachedCompanyUsers($isstatus = false){
    $cacheKey = 'company_users_' . Auth::id() . '_' . ($isstatus ? '1' : '0');
    return cache()->remember($cacheKey, 3600, function() use ($isstatus) {
        return CompanyUsers($isstatus);
    });
}

/**
 * Cache company multi-users for the current session
 * Reduces repeated User joins
 *
 * @return array
 */
function getCachedCompanyMultiUsers(){
    $cacheKey = 'company_multi_users_' . Auth::id();
    return cache()->remember($cacheKey, 3600, function() {
        return CompanyMultiUsers();
    });
}

/**
 * Cache quotation versions to avoid repeated database queries
 * Single query returns all versions instead of multiple individual queries
 *
 * @param int $quotationId
 * @return \Illuminate\Database\Eloquent\Collection
 */
function getCachedQuotationVersions($quotationId){
    $cacheKey = 'quotation_versions_' . $quotationId;
    return cache()->remember($cacheKey, 3600, function() use ($quotationId) {
        return QuotationVersion::where('quotation_id', $quotationId)->get();
    });
}

/**
 * Cache selected ironmongery by IDs to avoid N+1 queries
 * Batch fetches all selected ironmongery in single query
 *
 * @param array $ids
 * @return \Illuminate\Support\Collection
 */
function getCachedSelectedIronmongery($ids){
    if (empty($ids)) return collect();

    // Create a stable cache key based on sorted IDs
    sort($ids);
    $cacheKey = 'selected_ironmongery_' . md5(json_encode($ids)) . '_' . Auth::id();

    return cache()->remember($cacheKey, 3600, function() use ($ids) {
        return SelectedIronmongery::whereIn('id', array_unique($ids))
            ->where('UserId', Auth::user()->id)
            ->get()
            ->keyBy('id');
    });
}

/**
 * Cache ironmongery info models by IDs to avoid N+1 queries
 * Batch fetches all ironmongery info in single query
 *
 * @param array $ids
 * @return \Illuminate\Support\Collection
 */
function getCachedIronmongeryInfo($ids){
    if (empty($ids)) return collect();

    // Create a stable cache key based on sorted IDs
    sort($ids);
    $cacheKey = 'ironmongery_info_' . md5(json_encode($ids)) . '_' . Auth::id();

    return cache()->remember($cacheKey, 3600, function() use ($ids) {
        $result = IronmongeryInfoModel::whereIn('IronmongeryId', array_unique($ids))
            ->where('UserId', Auth::user()->id)
            ->get()
            ->groupBy('IronmongeryId');

        // Fallback if no results with UserId filter
        if ($result->isEmpty()) {
            $result = IronmongeryInfoModel::whereIn('id', array_unique($ids))
                ->get()
                ->groupBy('id');
        }

        return $result;
    });
}

/**
 * Clear all quotation-related caches
 * Call this after updating quotation data
 *
 * @param int $quotationId
 * @return void
 */
function clearQuotationCaches($quotationId){
    cache()->forget('quotation_versions_' . $quotationId);
}

/**
 * Clear all user-related caches
 * Call this after updating user permissions or company structure
 *
 * @param int $userId
 * @return void
 */
function clearUserCaches($userId){
    cache()->forget('company_users_' . $userId . '_0');
    cache()->forget('company_users_' . $userId . '_1');
    cache()->forget('company_multi_users_' . $userId);
}
