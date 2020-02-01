<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;

interface GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void;
}