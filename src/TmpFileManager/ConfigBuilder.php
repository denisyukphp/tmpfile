<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\CloseOpenedResourcesHandler\CloseOpenedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class ConfigBuilder
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

    public function setTemporaryDirectory(string $temporaryDirectory): self
    {
        $this->temporaryDirectory = $temporaryDirectory;

        return $this;
    }

    public function getTemporaryDirectory(): ?string
    {
        return $this->temporaryDirectory;
    }

    public function setTmpFilePrefix(string $tmpFilePrefix): self
    {
        $this->tmpFilePrefix = $tmpFilePrefix;

        return $this;
    }

    public function getTmpFilePrefix(): ?string
    {
        return $this->tmpFilePrefix;
    }

    public function setDeferredPurgeHandler(DeferredPurgeHandlerInterface $deferredPurgeHandler): self
    {
        $this->deferredPurgeHandler = $deferredPurgeHandler;

        return $this;
    }

    public function getDeferredPurgeHandler(): ?DeferredPurgeHandlerInterface
    {
        return $this->deferredPurgeHandler;
    }

    public function setCheckUnclosedResources(bool $checkUnclosedResources): self
    {
        $this->checkUnclosedResources = $checkUnclosedResources;

        return $this;
    }

    public function getCheckUnclosedResources(): ?bool
    {
        return $this->checkUnclosedResources;
    }

    public function setCloseOpenedResourcesHandler(CloseOpenedResourcesHandlerInterface $closeOpenedResourcesHandler): self
    {
        $this->closeOpenedResourcesHandler = $closeOpenedResourcesHandler;

        return $this;
    }

    public function getCloseOpenedResourcesHandler(): ?CloseOpenedResourcesHandlerInterface
    {
        return $this->closeOpenedResourcesHandler;
    }

    public function setGarageCollectionProbability(int $garageCollectionProbability): self
    {
        $this->garageCollectionProbability = $garageCollectionProbability;

        return $this;
    }

    public function getGarageCollectionProbability(): ?int
    {
        return $this->garageCollectionProbability;
    }

    public function setGarbageCollectionDivisor(int $garageCollectionDivisor): self
    {
        $this->garageCollectionDivisor = $garageCollectionDivisor;

        return $this;
    }

    public function getGarbageCollectionDivisor(): ?int
    {
        return $this->garageCollectionDivisor;
    }

    public function setGarbageCollectionLifetime(int $garageCollectionLifetime): self
    {
        $this->garageCollectionLifetime = $garageCollectionLifetime;

        return $this;
    }

    public function getGarbageCollectionLifetime(): ?int
    {
        return $this->garageCollectionLifetime;
    }

    public function setGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $this->garageCollectionHandler = $garbageCollectionHandler;

        return $this;
    }

    public function getGarbageCollectionHandler(): ?GarbageCollectionHandlerInterface
    {
        return $this->garageCollectionHandler;
    }

    public function build(): Config
    {
        return new Config($this);
    }
}