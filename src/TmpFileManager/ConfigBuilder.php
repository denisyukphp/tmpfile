<?php

namespace TmpFile\TmpFileManager;

use TmpFile\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFile\TmpFileManager\DeferredPurgeHandler\ShutdownDeferredPurgeHandler;

class ConfigBuilder
{
    private $temporaryDirectory;

    private $tmpFilePrefix;

    private $deferredPurgeHandler;

    private $checkUnclosedResources;

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