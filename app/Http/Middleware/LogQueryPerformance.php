<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LogQueryPerformance
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('app.enable_query_logging', true)) {
            return $next($request);
        }

        // Enable query logging
        DB::enableQueryLog();
        $startTime = microtime(true);

        $response = $next($request);

        // Get execution details
        $queries = DB::getQueryLog();
        $executionTime = (microtime(true) - $startTime) * 1000; // Convert to ms

        // Log performance data
        $this->logPerformance($request, $queries, $executionTime);

        DB::disableQueryLog();

        return $response;
    }

    /**
     * Log request performance and query details
     */
    private function logPerformance(Request $request, array $queries, float $executionTime): void
    {
        $slowQueryCount = 0;
        $thresholdMs = config('app.query_log_threshold', 100);

        // Find slow queries
        foreach ($queries as $query) {
            if (($query['time'] ?? 0) > $thresholdMs) {
                $slowQueryCount++;
                Log::channel('queries')->warning('🐌 SLOW QUERY', [
                    'url' => $request->path(),
                    'method' => $request->method(),
                    'query' => $query['query'],
                    'bindings' => $query['bindings'],
                    'time_ms' => $query['time'],
                ]);
            }
        }

        // Log request summary
        $summary = [
            'path' => $request->path(),
            'method' => $request->method(),
            'total_time_ms' => round($executionTime, 2),
            'total_queries' => count($queries),
            'slow_queries' => $slowQueryCount,
            'ip' => $request->ip(),
        ];

        if (config('app.debug_queries', false)) {
            Log::channel('queries')->debug('📊 REQUEST SUMMARY', $summary);
        } elseif ($slowQueryCount > 0) {
            Log::channel('queries')->info('⚠️ REQUEST HAD SLOW QUERIES', $summary);
        }
    }
}
