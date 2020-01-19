<?php

namespace TmpFile;

use TmpFile\DeferredPurgeHandler\DeferredPurgeHandlerInterface;

interface ConfigInterface
{
    public function getTemporaryDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function getCheckUnclosedResources(): bool;
}