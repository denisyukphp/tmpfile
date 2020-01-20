<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

class ShutdownDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}