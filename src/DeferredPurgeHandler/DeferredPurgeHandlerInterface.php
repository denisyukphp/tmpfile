<?php

namespace TmpFile\DeferredPurgeHandler;

use TmpFile\TmpFileManager;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void;
}