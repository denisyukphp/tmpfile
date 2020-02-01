<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;

class VoidGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void
    {
    }
}