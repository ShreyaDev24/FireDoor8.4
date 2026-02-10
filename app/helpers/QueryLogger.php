<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QueryLogger
{
    /**
     * Enable query logging with execution time tracking
     * This will log all queries that exceed the threshold
     */
    public static function enableQueryLogging()
    {
        $thresholdMs = config('app.query_log_threshold', 100); // Log queries taking > 100ms
        $queryStartTimes = [];

        // Listen to database queries
        DB::listen(function ($query) use (&$queryStartTimes, $thresholdMs) {
            $startTime = microtime(true);

            // Get current query time if available
            if (isset($queryStartTimes[$query->hash])) {
                $executionTime = (microtime(true) - $queryStartTimes[$query->hash]) * 1000; // Convert to ms

                // Format the log message
                $logData = [
                    'query' => $query->sql,
                    'bindings' => $query->bindings,
                    'execution_time_ms' => round($executionTime, 2),
                    'threshold_ms' => $thresholdMs,
                ];

                // Log queries that exceed threshold
                if ($executionTime > $thresholdMs) {
                    Log::warning('SLOW QUERY DETECTED', $logData);
                }

                // Always log for detailed analysis
                if (config('app.debug_queries', false)) {
                    Log::info('QUERY EXECUTED', $logData);
                }

                unset($queryStartTimes[$query->hash]);
            } else {
                $queryStartTimes[$query->hash] = microtime(true);
            }
        });
    }

    /**
     * Log a specific operation's query performance
     * Usage in controller: QueryLogger::logOperationTime('user.index', function() { ... })
     */
    public static function logOperationTime($operationName, callable $callback)
    {
        $startTime = microtime(true);

        DB::enableQueryLog();

        $result = $callback();

        $executionTime = (microtime(true) - $startTime) * 1000; // Convert to ms
        $queries = DB::getQueryLog();

        $logData = [
            'operation' => $operationName,
            'total_time_ms' => round($executionTime, 2),
            'query_count' => count($queries),
            'queries' => $queries,
        ];

        Log::info('OPERATION PERFORMANCE', $logData);

        DB::disableQueryLog();

        return $result;
    }

    /**
     * Get all slow queries from logs
     * Usage: QueryLogger::getSlowestQueries(5)
     */
    public static function logQuerySummary()
    {
        if (!config('app.debug_queries', false)) {
            return;
        }

        $queries = DB::getQueryLog();

        if (empty($queries)) {
            return;
        }

        $summary = [
            'total_queries' => count($queries),
            'total_time_ms' => collect($queries)->sum('time') ?? 0,
            'queries' => $queries,
        ];

        Log::info('QUERY SUMMARY', $summary);
    }
}
