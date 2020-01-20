<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

class ShutdownDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        $callback = function () use ($tmpFileManager) {
            $tmpFileManager->purge();
        };

        register_shutdown_function($callback);
    }
}