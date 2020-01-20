<?php

namespace TmpFile\TmpFileManager\DeferredPurgeHandler;

use TmpFile\TmpFileManager\TmpFileManager;

final class DummyDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
    }
}