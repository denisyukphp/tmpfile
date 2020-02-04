<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

use TmpFile\TmpFile;

interface CloseOpenedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void;
}