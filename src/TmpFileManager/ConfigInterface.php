<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\CloseOpenedResourcesHandler\CloseOpenedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

interface ConfigInterface
{
    public function getTemporaryDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function getCheckUnclosedResources(): bool;

    public function getCloseOpenedResourcesHandler(): CloseOpenedResourcesHandlerInterface;

    public function getGarbageCollectionProbability(): int;

    public function getGarbageCollectionDivisor(): int;

    public function getGarbageCollectionLifetime(): int;

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface;
}