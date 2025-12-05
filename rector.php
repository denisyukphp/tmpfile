<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withCache(
        cacheDirectory: __DIR__.'/build/cache/rector',
        cacheClass: FileCacheStorage::class,
    )
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withPhpSets(
        php80: true,
    )
    ->withSets([
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
    ])
;
