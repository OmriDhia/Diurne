<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    // Define the paths where Rector should apply transformations
    $rectorConfig->paths([__DIR__ . '/src']);

    // Enable rules for upgrading to Symfony 7
    $rectorConfig->sets([
        SymfonySetList::SYMFONY_70,
        LevelSetList::UP_TO_PHP_84, // PHP 8.4 compatibility
    ]);

    // Optional: enable auto-imports for cleaner code
    $rectorConfig->importNames();
};
