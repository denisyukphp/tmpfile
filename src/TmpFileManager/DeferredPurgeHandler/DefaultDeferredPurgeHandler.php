<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;

class DefaultDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}