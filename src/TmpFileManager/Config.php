<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\DeferredPurgeHandler\DefaultDeferredPurgeHandler;
use TmpFileManager\CloseOpenedResourcesHandler\CloseOpenedResourcesHandlerInterface;
use TmpFileManager\CloseOpenedResourcesHandler\DefaultCloseOpenedResourcesHandler;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\DefaultGarbageCollectionHandler;

class Config implements ConfigInterface
{
    private
        $temporaryDirectory,
        $tmpFilePrefix,
        $deferredPurgeHandler,
        $checkUnclosedResources,
        $closeOpenedResourcesHandler,
        $garageCollectionProbability,
        $garageCollectionDivisor,
        $garageCollectionLifetime,
        $garageCollectionHandler
    ;

    public function __construct(ConfigBuilder $configBuilder)
    {
        $this->temporaryDirectory = $configBuilder->getTemporaryDirectory();
        $this->tmpFilePrefix = $configBuilder->getTmpFilePrefix();
        $this->deferredPurgeHandler = $configBuilder->getDeferredPurgeHandler();
        $this->checkUnclosedResources = $configBuilder->getCheckUnclosedResources();
        $this->closeOpenedResourcesHandler = $configBuilder->getCloseOpenedResourcesHandler();
        $this->garageCollectionProbability = $configBuilder->getGarageCollectionProbability();
        $this->garageCollectionDivisor = $configBuilder->getGarbageCollectionDivisor();
        $this->garageCollectionLifetime = $configBuilder->getGarbageCollectionLifetime();
        $this->garageCollectionHandler = $configBuilder->getGarbageCollectionHandler();
    }

    public function getTemporaryDirectory(): string
    {
        if (!$this->temporaryDirectory) {
            $this->temporaryDirectory = sys_get_temp_dir();
        }

        return $this->temporaryDirectory;
    }

    public function getTmpFilePrefix(): string
    {
        if (!$this->tmpFilePrefix) {
            $this->tmpFilePrefix = 'php';
        }

        return $this->tmpFilePrefix;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        if (!$this->deferredPurgeHandler) {
            $this->deferredPurgeHandler = new DefaultDeferredPurgeHandler();
        }

        return $this->deferredPurgeHandler;
    }

    public function getCheckUnclosedResources(): bool
    {
        if (!$this->checkUnclosedResources) {
            $this->checkUnclosedResources = true;
        }

        return $this->checkUnclosedResources;
    }

    public function getCloseOpenedResourcesHandler(): CloseOpenedResourcesHandlerInterface
    {
        if (!$this->closeOpenedResourcesHandler) {
            $this->closeOpenedResourcesHandler = new DefaultCloseOpenedResourcesHandler();
        }

        return $this->closeOpenedResourcesHandler;
    }

    public function getGarbageCollectionProbability(): int
    {
        if (!$this->garageCollectionProbability) {
            $this->garageCollectionProbability = 0;
        }

        return $this->garageCollectionProbability;
    }

    public function getGarbageCollectionDivisor(): int
    {
        if (!$this->garageCollectionDivisor) {
            $this->garageCollectionDivisor = 100;
        }

        return $this->garageCollectionDivisor;
    }

    public function getGarbageCollectionLifetime(): int
    {
        if (!$this->garageCollectionLifetime) {
            $this->garageCollectionLifetime = 3600;
        }

        return $this->garageCollectionLifetime;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        if (!$this->garageCollectionHandler) {
            $this->garageCollectionHandler = new DefaultGarbageCollectionHandler();
        }

        return $this->garageCollectionHandler;
    }
}