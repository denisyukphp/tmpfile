<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

class DestructDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        new class($callback)
        {
            private $callback;

            public function __construct(callable $callback)
            {
                $this->callback = $callback;
            }

            public function __destruct()
            {
                call_user_func($this->callback);
            }
        };
    }
}