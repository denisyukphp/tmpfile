<?php

namespace TmpFile\TmpFileManager\DeferredPurgeHandler;

use TmpFile\TmpFileManager\TmpFileManager;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void;
}