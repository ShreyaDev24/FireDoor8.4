<?php

use Symplify\EasyParallel\ValueObject\EasyParallelConfig;

return static function (EasyParallelConfig $config): void {
    $config->setJobTimeoutInSeconds(300); // Timeout per batch = 5 mins
};
