<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Enable query logging if configured
        if ($this->shouldEnableQueryLogging()) {
            $this->enableDetailedQueryLogging();
        }
    }

    /**
     * Determine if query logging should be enabled
     */
    private function shouldEnableQueryLogging(): bool
    {
        return config('app.enable_query_logging', true) || config('app.debug_queries', false);
    }

    /**
     * Enable detailed query logging with timing information
     */
    private function enableDetailedQueryLogging(): void
    {
        $thresholdMs = config('app.query_log_threshold', 100); // milliseconds
        $logSlowQueries = config('app.log_slow_queries', true);
        $logAllQueries = config('app.debug_queries', false);

        DB::listen(function ($query) use ($thresholdMs, $logSlowQueries, $logAllQueries) {
            $executionTime = $query->time ?? 0; // execution time in milliseconds

            $logData = [
                'query' => $query->sql,
                'bindings' => $query->bindings,
                'execution_time_ms' => $executionTime,
                'connection' => $query->connectionName,
            ];

            // Log slow queries
            if ($logSlowQueries && $executionTime > $thresholdMs) {
                Log::channel('queries')->warning('⚠️ SLOW QUERY DETECTED', $logData);
            }

            // Log all queries when debug is enabled
            if ($logAllQueries) {
                Log::channel('queries')->debug('📊 QUERY EXECUTED', $logData);
            }
        });
    }
}
