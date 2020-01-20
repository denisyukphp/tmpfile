<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void;
}