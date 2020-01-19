<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

final class DummyDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
    }
}