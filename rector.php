<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    // Paths to scan
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/storage',
        __DIR__ . '/bootstrap/cache',
    ]);


    // Rule sets to apply
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
    ]);

    // Run with parallel processing (2 workers, 10 jobs per worker, stop after 50 errors)
    $rectorConfig->parallel(2, 10, 50);
};
