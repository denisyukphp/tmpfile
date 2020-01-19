<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

class ShutdownDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        register_shutdown_function(function () use ($tmpFileManager) {
            $tmpFileManager->purge();
        });
    }
}