<?php

namespace TmpFile;

use TmpFile\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFile\DeferredPurgeHandler\ShutdownDeferredPurgeHandler;

class ConfigBuilder
{
    /**
     * @var string $temporaryDirectory
     * @var string $tmpFilePrefix
     * @var DeferredPurgeHandlerInterface $deferredPurgeHandler
     * @var bool $checkUnclosedResources
     */
    protected
        $temporaryDirectory,
        $tmpFilePrefix,
        $deferredPurgeHandler,
        $checkUnclosedResources
    ;

    public function setTemporaryDirectory(string $temporaryDirectory): self
    {
        $this->temporaryDirectory = $temporaryDirectory;

        return $this;
    }

    public function getTemporaryDirectory(): string
    {
        if (!$this->temporaryDirectory) {
            $this->temporaryDirectory = sys_get_temp_dir();
        }

        return $this->temporaryDirectory;
    }

    public function setTmpFilePrefix(string $tmpFilePrefix): self
    {
        $this->tmpFilePrefix = $tmpFilePrefix;

        return $this;
    }

    public function getTmpFilePrefix(): string
    {
        if (!$this->tmpFilePrefix) {
            $this->tmpFilePrefix = 'php';
        }

        return $this->tmpFilePrefix;
    }

    public function setDeferredPurgeHandler(DeferredPurgeHandlerInterface $deferredPurgeHandler): self
    {
        $this->deferredPurgeHandler = $deferredPurgeHandler;

        return $this;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        if (!$this->deferredPurgeHandler) {
            $this->deferredPurgeHandler = new ShutdownDeferredPurgeHandler();
        }

        return $this->deferredPurgeHandler;
    }

    public function setCheckUnclosedResources(bool $checkUnclosedResources): self
    {
        $this->checkUnclosedResources = $checkUnclosedResources;

        return $this;
    }

    public function getCheckUnclosedResources(): bool
    {
        if (!$this->checkUnclosedResources) {
            $this->checkUnclosedResources = true;
        }

        return $this->checkUnclosedResources;
    }

    public function build(): Config
    {
        return new Config($this);
    }
}