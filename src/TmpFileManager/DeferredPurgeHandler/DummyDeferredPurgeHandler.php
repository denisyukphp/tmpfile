<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;

final class DummyDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
    }
}