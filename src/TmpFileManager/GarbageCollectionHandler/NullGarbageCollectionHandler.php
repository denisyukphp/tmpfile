<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;

class NullGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void
    {
    }
}