<?php

namespace TmpFile\TmpFileManager;

use TmpFile\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;

class Config implements ConfigInterface
{
    protected $configBuilder;

    public function __construct(ConfigBuilder $configBuilder)
    {
        $this->configBuilder = $configBuilder;
    }

    public function getTemporaryDirectory(): string
    {
        return $this->configBuilder->getTemporaryDirectory();
    }

    public function getTmpFilePrefix(): string
    {
        return $this->configBuilder->getTmpFilePrefix();
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->configBuilder->getDeferredPurgeHandler();
    }

    public function getCheckUnclosedResources(): bool
    {
        return $this->configBuilder->getCheckUnclosedResources();
    }
}