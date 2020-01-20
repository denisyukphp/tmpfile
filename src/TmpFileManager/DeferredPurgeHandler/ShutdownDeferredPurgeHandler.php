<?php

namespace TmpFile\TmpFileManager\DeferredPurgeHandler;

use TmpFile\TmpFileManager\TmpFileManager;

class ShutdownDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}